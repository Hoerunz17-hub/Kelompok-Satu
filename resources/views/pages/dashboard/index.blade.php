@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <div class="row" id="proBanner">
            <div>
                <span>
                    <a href="https://github.com/BootstrapDash/ConnectPlusAdmin-Free-Bootstrap-Admin-Template"></a>
                    <a href="http://www.bootstrapdash.com/demo/connect-plus/jquery/template/"></a>
                    <i class="mdi mdi-close" id="bannerClose"></i>
                </span>
            </div>
        </div>
        <div class="d-xl-flex justify-content-between align-items-start">
            <h2 class="text-dark font-weight-bold mb-2"> Service Laptop Source </h2>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="tab-content tab-transparent-content">
                    <div class="tab-pane fade show active" id="business-1" role="tabpanel" aria-labelledby="business-tab">
                        <div class="row">
                            <!-- Total Service -->
                            <div class="col-xl-3 col-lg-6 col-sm-6 grid-margin stretch-card">
                                <div class="card">
                                    <div
                                        class="card-body text-center d-flex flex-column align-items-center justify-content-center">
                                        <h5 class="mb-2 text-dark font-weight-normal">Total Service</h5>
                                        <h2 class="mb-4 text-dark font-weight-bold">{{ $totalService }}</h2>
                                        <!-- ubah mb-3 ke mb-4 -->
                                        <div class="dashboard-progress dashboard-progress-1 d-flex align-items-center justify-content-center"
                                            style="width:90px; height:90px; position:relative;">
                                            <i class="mdi mdi-laptop text-primary"
                                                style="font-size:38px; position:absolute;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Customers -->
                            <div class="col-xl-3 col-lg-6 col-sm-6 grid-margin stretch-card">
                                <div class="card">
                                    <div
                                        class="card-body text-center d-flex flex-column align-items-center justify-content-center">
                                        <h5 class="mb-2 text-dark font-weight-normal">Total Customers</h5>
                                        <h2 class="mb-4 text-dark font-weight-bold">{{ $totalCustomer }}</h2>
                                        <div class="dashboard-progress dashboard-progress-2 d-flex align-items-center justify-content-center"
                                            style="width:90px; height:90px; position:relative;">
                                            <i class="mdi mdi-account-group text-success"
                                                style="font-size:38px; position:absolute;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Technicians -->
                            <div class="col-xl-3 col-lg-6 col-sm-6 grid-margin stretch-card">
                                <div class="card">
                                    <div
                                        class="card-body text-center d-flex flex-column align-items-center justify-content-center">
                                        <h5 class="mb-2 text-dark font-weight-normal">Total Technicians</h5>
                                        <h2 class="mb-4 text-dark font-weight-bold">{{ $totalTechnician }}</h2>
                                        <div class="dashboard-progress dashboard-progress-3 d-flex align-items-center justify-content-center"
                                            style="width:90px; height:90px; position:relative;">
                                            <i class="mdi mdi-wrench text-warning"
                                                style="font-size:38px; position:absolute;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Revenue -->
                            <div class="col-xl-3 col-lg-6 col-sm-6 grid-margin stretch-card">
                                <div class="card">
                                    <div
                                        class="card-body text-center d-flex flex-column align-items-center justify-content-center">
                                        <h5 class="mb-2 text-dark font-weight-normal">Total Revenue</h5>
                                        <h2 class="mb-4 text-dark font-weight-bold">Rp
                                            {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
                                        <div class="dashboard-progress dashboard-progress-4 d-flex align-items-center justify-content-center"
                                            style="width:90px; height:90px; position:relative;">
                                            <i class="mdi mdi-cash-multiple text-danger"
                                                style="font-size:38px; position:absolute;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>






                        <!-- Status Service Badges -->
                        <div class="row mt-4 justify-content-center">
                            @php
                                $statuses = [
                                    'accepted' => [
                                        'color' => 'primary',
                                        'icon' => 'mdi-check-circle-outline',
                                        'label' => 'Accepted',
                                    ],
                                    'process' => [
                                        'color' => 'warning',
                                        'icon' => 'mdi-autorenew',
                                        'label' => 'In Process',
                                    ],
                                    'finished' => [
                                        'color' => 'success',
                                        'icon' => 'mdi-clipboard-check-outline',
                                        'label' => 'Finished',
                                    ],
                                    'taken' => ['color' => 'info', 'icon' => 'mdi-truck', 'label' => 'Taken'],
                                    'cancelled' => [
                                        'color' => 'danger',
                                        'icon' => 'mdi-cancel',
                                        'label' => 'Cancelled',
                                    ],
                                ];
                            @endphp

                            @foreach ($statuses as $status => $props)
                                @php
                                    $count = \App\Models\Services::where('status', $status)->count();
                                @endphp
                                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6 mb-3 d-flex justify-content-center">
                                    <div class="card text-center shadow-sm" style="width: 150px; min-height: 160px;">
                                        <div
                                            class="card-body d-flex flex-column align-items-center justify-content-center p-3">
                                            <i
                                                class="mdi {{ $props['icon'] }} mdi-48px mb-2 text-{{ $props['color'] }}"></i>
                                            <h6 class="mb-2 text-dark">{{ $props['label'] }}</h6>
                                            <span class="badge badge-{{ $props['color'] }} p-2"
                                                style="font-size:1rem; min-width: 35px;">{{ $count }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>



                        <!-- Chart -->
                        <div class="row">
                            <div class="col-12 grid-margin">
                                <div class="card" style="height:350px;"> <!-- batasi tinggi card -->
                                    <div class="card-body" style="height:100%; padding:1rem;">
                                        <h4 class="card-title">Revenue per Bulan (Rp)</h4>
                                        <canvas id="areachart-revenue" style="height:100%; width:100%;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Chart: Brand Laptop yang Sering Diservice -->
                        <div class="row mt-4">
                            <div class="col-12 grid-margin">
                                <div class="card" style="height:350px;"> <!-- samakan tinggi dan lebar dengan revenue -->
                                    <div class="card-body" style="height:100%; padding:1rem;">
                                        <h4 class="card-title text-dark mb-3">💻 Merek Laptop yang Sering Diservice</h4>
                                        <canvas id="chartBrand" style="height:100%; width:100%;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        #areachart-multi {
            width: 100% !important;
            height: 300px !important;
        }

        /* Optional: buat card lebih “floating” dan terlihat rapi */
        .card.text-center.shadow-sm {
            border-radius: 12px;
            transition: transform 0.2s;
        }

        .card.text-center.shadow-sm:hover {
            transform: translateY(-3px);
        }

        /* Badge center dan proporsional */
        .badge {
            display: inline-block;
            text-align: center;
        }

        #chartBrand {
            max-height: 300px;
        }
    </style>



    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById("areachart-revenue").getContext("2d");

            // Gradasi lembut dari atas ke bawah
            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, "rgba(75,192,192,0.4)");
            gradient.addColorStop(1, "rgba(75,192,192,0)");

            new Chart(ctx, {
                type: "line",
                data: {
                    labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov",
                        "Des"
                    ],
                    datasets: [{
                        label: "Revenue (Rp)",
                        data: @json($chartRevenue),
                        fill: true,
                        backgroundColor: gradient,
                        borderColor: "rgba(75, 192, 192, 1)",
                        borderWidth: 2,
                        pointBackgroundColor: "rgba(75, 192, 192, 1)",
                        pointBorderColor: "#fff",
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true
                        },
                        tooltip: {
                            usePointStyle: true,
                            backgroundColor: "rgba(0,0,0,0.8)",
                            padding: 12,
                            cornerRadius: 8,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            callbacks: {
                                // Tooltip title → bulan
                                title: function(context) {
                                    return "Bulan: " + context[0].label;
                                },
                                // Tooltip isi → revenue format rupiah
                                label: function(context) {
                                    const value = context.parsed.y || 0;
                                    return "Total Revenue: Rp " + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    animation: {
                        duration: 1500,
                        easing: 'easeOutQuart',
                        delay: (context) => {
                            let delay = 0;
                            if (context.type === 'data' && context.mode === 'default' && !context
                                .dropped) {
                                delay = context.dataIndex * 150;
                                context.dropped = true;
                            }
                            return delay;
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: "rgba(0,0,0,0.05)"
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: "rgba(0,0,0,0.05)"
                            },
                            title: {
                                display: true,
                                text: "Revenue (Rp)"
                            },
                            suggestedMax: (() => {
                                const data = @json($chartRevenue);
                                const maxVal = Math.max(...data);
                                return maxVal > 0 ? maxVal * 1.5 : 100000;
                            })(),
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            }); // === CHART 2: Brand Laptop yang Sering Diservice (Horizontal)
            const brandLabels = @json($brandLabels);
            const brandCounts = @json($brandCounts);
            const brandColors = @json($brandColors);

            new Chart(document.getElementById("chartBrand"), {
                type: "bar",
                data: {
                    labels: brandLabels,
                    datasets: [{
                        label: "Jumlah Service",
                        data: brandCounts,
                        backgroundColor: brandColors,
                        borderRadius: 8,
                        barThickness: 25,
                        categoryPercentage: 0.6, // ✅ jarak antar batang
                        barPercentage: 0.9 // ✅ lebar batang
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: "rgba(0,0,0,0.8)",
                            titleFont: {
                                size: 13,
                                weight: "bold"
                            },
                            bodyFont: {
                                size: 12
                            },
                            callbacks: {
                                label: ctx => `${ctx.raw} kali diservice`
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true, // ✅ mulai dari 0
                            min: 0,
                            ticks: {
                                stepSize: 1,
                                color: "#555",
                                font: {
                                    size: 12
                                }
                            },
                            grid: {
                                color: "rgba(0,0,0,0.05)"
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: "#333",
                                font: {
                                    size: 13,
                                    weight: '500'
                                }
                            }
                        }
                    },
                    animation: {
                        duration: 1200,
                        easing: "easeOutQuart"
                    }
                }
            });

        });
    </script>
@endsection
