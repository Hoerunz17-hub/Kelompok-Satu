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
    'received_date' => $request->received_date,
    'completed_date' => $request->completed_date,
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

    $action = $request->input('action');

    if ($action === 'status') {
        $request->validate(['status' => 'required|string']);
        $service->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'Status service berhasil diperbarui!');
    }

    // Update payment + status
    $totalPrice = $service->details->sum('price');
    $otherCost = $clean($request->other_cost);
    $paid = $clean($request->paid);
    $grandTotal = $totalPrice + $otherCost;

   // Tentukan status pembayaran otomatis
if ($paid <= 0) {
    $statusPaid = 'unpaid'; // Belum bayar sama sekali
} elseif ($paid < $grandTotal) {
    $statusPaid = 'debt'; // Nyicil / belum lunas
} else {
    $statusPaid = 'paid'; // Sudah lunas
}


   $change = max(0, $paid - $grandTotal);

$service->update([
    'status' => $request->status,
    'paymentmethod' => $request->paymentmethod,
    'other_cost' => $otherCost,
    'paid' => $paid,
    'change' => $change,
    'estimated_cost' => $grandTotal,
    'status_paid' => $statusPaid,
]);

    return redirect('/service')->with('success', 'Payment updated successfully!');
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
