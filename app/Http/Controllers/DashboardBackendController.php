<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Services;
use App\Models\Usertiga;

class DashboardBackendController extends Controller
{
    public function index()
    {
        // === Total Service (termasuk soft deleted) ===
        $totalService = Services::withTrashed()->count();
        $completedService = Services::where('status', 'finished')->count();

        // === Total Customer & Technician (termasuk soft deleted) ===
        $totalCustomer = Usertiga::withTrashed()->where('role', 'customer')->count();
        $totalTechnician = Usertiga::withTrashed()->where('role', 'technician')->count();

        // === Total Revenue hanya hitung yang aktif ===
        $totalRevenue = Services::sum('paid');

        $completedPercent = $totalService ? round(($completedService / $totalService) * 100) : 0;

        // === Chart per bulan ===
        $monthlyServices = Services::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $monthlyRevenue = Services::selectRaw('MONTH(created_at) as month, SUM(paid) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $monthlyFinished = Services::where('status', 'finished')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $chartData = [];
        $chartRevenue = [];
        $chartFinished = [];

        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $monthlyServices[$i] ?? 0;
            $chartRevenue[] = $monthlyRevenue[$i] ?? 0;
            $chartFinished[] = $monthlyFinished[$i] ?? 0;
        }

        // === Chart Brand Laptop (termasuk laptop soft deleted) ===
        $brandData = Services::with(['laptop' => function($q) { $q->withTrashed(); }]) // pastikan relasi laptop termasuk soft deleted
            ->get()
            ->groupBy(function($service){
                return $service->laptop ? $service->laptop->brand : 'Unknown';
            })
            ->map(function($group){
                return $group->count();
            });

        $brandLabels = $brandData->keys();
        $brandCounts = $brandData->values();

        // generate warna random (dalam format hex)
        $brandColors = $brandLabels->map(function () {
            return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
        });

        // === Kirim semua data ke view ===
        return view('pages.dashboard.index', compact(
            'totalService',
            'completedService',
            'totalCustomer',
            'totalTechnician',
            'totalRevenue',
            'completedPercent',
            'chartData',
            'chartRevenue',
            'chartFinished',
            'brandLabels',
            'brandCounts',
             'brandColors'
        ));
    }
}
