<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Laptop;
use App\Models\ServiceItem;
use App\Models\ServiceDetail;
use App\Models\Services;
use App\Models\Usertiga;
use Illuminate\Support\Facades\DB;

class ServiceBackendController extends Controller
{
    public function index()
    {
        $services = Services::with(['customer', 'technician', 'laptop'])->get();
        return view('pages.service.index', compact('services'));
    }

    public function create()
    {
        $customers = Usertiga::where('role', 'customer')->get();
        $technicians = Usertiga::where('role', 'technician')->get();
        $laptops = Laptop::where('is_active', 'active')->get();
        $serviceItems = ServiceItem::where('is_active', 'active')->get();

        return view('pages.service.create', compact('customers', 'technicians', 'laptops', 'serviceItems'));
    }

    private function generateInvoiceNumber()
    {
        $today = now()->format('Ymd'); // contoh: 20251012
        $prefix = 'INV-' . $today . '-';

        $lastInvoice = Services::whereDate('created_at', now())
            ->orderBy('id', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->no_invoice, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return $prefix . $newNumber;
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $clean = fn($val) => (float) str_replace(['.', ','], ['', '.'], preg_replace('/[^\d.,]/', '', $val ?? '0'));

            $total_cost = 0;

            // Hitung total dari semua price di tabel service
            if ($request->has('price')) {
                foreach ($request->price as $p) {
                    $total_cost += $clean($p);
                }
            }

            // Simpan data utama service
       $service = Services::create([
    'no_invoice' => $this->generateInvoiceNumber(),
    'customer_id' => $request->customer_id,
    'technician_id' => $request->technician_id,
    'laptop_id' => $request->laptop_id,
    'damage_description' => $request->damage_description,
    'total_cost' => $total_cost,
    'status' => $request->status ?: 'Accepted', // ✅ default Accepted kalau kosong
            'status_paid' => 'Unpaid', // ✅ default Unpaid saat create

]);


            // Simpan detail per service item
            if ($request->has('serviceitem_id')) {
                foreach ($request->serviceitem_id as $key => $itemId) {
                    ServiceDetail::create([
                        'service_id' => $service->id,
                        'serviceitem_id' => $itemId,
                        'price' => $clean($request->price[$key] ?? 0),
                    ]);
                }
            }

            DB::commit();
            return redirect('/service')->with('success', 'Service berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

            public function show($id)
        {
            // Ambil data service beserta relasinya
            $service = Services::with([
                'customer',
                'technician',
                'laptop',
                'details.serviceItem'
            ])->findOrFail($id);

            return view('pages.service.detail', compact('service'));
        }

 public function updatePayment(Request $request, $id)
{
    $service = Services::findOrFail($id);

    $clean = fn($val) => (float) str_replace(['.', ','], ['', '.'], preg_replace('/[^\d.,]/', '', $val ?? '0'));
    $totalPrice = $service->details->sum('price');

    // === Simpan nilai lama ===
    $old = [
        'status' => $service->status,
        'completed_date' => $service->completed_date,
        'received_date' => $service->received_date,
        'paid' => $service->paid ?? 0,
        'paymentmethod' => $service->paymentmethod,
        'other_cost' => $service->other_cost ?? 0,
        'status_paid' => $service->status_paid,
        'change' => $service->change ?? 0,
    ];

    // === Ambil nilai baru ===
    $otherCost = $request->filled('other_cost') ? $clean($request->other_cost) : $old['other_cost'];
    $paidNow   = $request->filled('paid') ? $clean($request->paid) : 0;
    $newPaid   = $old['paid'] + $paidNow;
    $grandTotal = $totalPrice + $otherCost;
    $change = max(0, $newPaid - $grandTotal);

    // === Status Paid Otomatis ===
    if ($newPaid <= 0) {
        $statusPaid = 'unpaid';
    } elseif ($newPaid < $grandTotal) {
        $statusPaid = 'debt';
    } else {
        $statusPaid = 'paid';
    }

    // === Kalau tidak ubah pembayaran ===
    if (!$request->filled('paid') && !$request->filled('other_cost')) {
        $newPaid = $old['paid'];
        $otherCost = $old['other_cost'];
        $change = $old['change'];
        $statusPaid = $old['status_paid'];
    }

    // === Update data ===
    $service->update([
        'status' => $request->status ?? $service->status,
        'paymentmethod' => $request->paymentmethod ?? $service->paymentmethod,
        'other_cost' => $otherCost,
        'paid' => $newPaid,
        'change' => $change,
        'estimated_cost' => $grandTotal,
        'status_paid' => $statusPaid,
        'received_date' => $request->received_date ?? $service->received_date,
        'completed_date' => $request->completed_date ?? $service->completed_date,
    ]);

    // === Deteksi perubahan satu per satu ===
    $changes = [];

    if ($old['status'] !== $service->status) {
        $changes[] = 'Status service';
    }
    if ($old['completed_date'] !== $service->completed_date || $old['received_date'] !== $service->received_date) {
        $changes[] = 'Tanggal';
    }
    if (
        $old['paid'] !== $service->paid ||
        $old['paymentmethod'] !== $service->paymentmethod ||
        $old['other_cost'] !== $service->other_cost ||
        $old['status_paid'] !== $service->status_paid
    ) {
        $changes[] = 'Pembayaran';
    }

        // === Buat pesan ===
    if (empty($changes)) {
        $message = 'ℹ️ Tidak ada perubahan data.';
    } else {
        $message = '✅ ' . implode(', ', $changes) . ' berhasil diperbarui!';
    }

    // Tambahkan kembalian hanya jika pembayaran berubah DAN ada change baru
    if (in_array('Pembayaran', $changes) && $change > 0) {
        $message .= ' 💰 Kembalian: Rp ' . number_format($change, 0, ',', '.');
    }

    return redirect('/service')->with('success', $message);

    }




    public function destroy($id)
    {
        $service = Services::find($id);

        if ($service) {
            $service->delete();
            return redirect('/service')->with('success', 'Service berhasil dihapus!');
        }

        return redirect('/service')->with('error', 'Service tidak ditemukan!');
    }
}
