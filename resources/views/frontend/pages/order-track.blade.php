@extends('frontend.layouts.master')

@section('title','Lacak Order | PT Patin Nusantara GlobalIndo Deli')

@section('main-content')
    <!-- Breadcrumbs -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread-inner">
                        <ul class="bread-list">
                            <li><a href="{{route('home')}}">Beranda<i class="ti-arrow-right"></i></a></li>
                            <li class="active"><a href="javascript:void(0);">Lacak Order</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumbs -->

    <section class="tracking_box_area section_gap py-5">
        <div class="container">
            <div class="tracking_box_inner">
            <p>Untuk melacak pesanan Anda, silakan masukkan nomor order pada kolom di bawah ini, lalu tekan tombol <strong>"Lacak Pesanan"</strong>. Nomor order dapat ditemukan pada <strong>struk pembelian yang dapat dilihat di dashboard</strong> yang kami kirim setelah Anda melakukan pemesanan.</p>
                <form class="row tracking_form my-4" action="{{ route('product.track.order') }}" method="POST">
                    @csrf
                    <div class="col-md-8 form-group">
                        <input type="text" class="form-control p-2" name="order_number" 
                               placeholder="Masukkan nomor order" 
                               value="{{ isset($order) ? $order->order_number : old('order_number') }}" required>
                    </div>

                    @if(isset($order))
                    <!-- Order Tracking Timeline -->
                    <div class="col-md-12 my-4">
                        <div class="order-tracking-card">
                            <div class="order-header">
                                <h5>Nomor Order: <strong>{{ $order->order_number }}</strong></h5>
                                <p class="order-status">
                                    Status: 
                                    <span class="badge badge-{{ $order->status == 'cancel' ? 'danger' : 'success' }}">
                                        @if($order->status == 'new') Pesanan Baru
                                        @elseif($order->status == 'process') Diproses
                                        @elseif($order->status == 'delivered') Diterima
                                        @else Dibatalkan
                                        @endif
                                    </span>
                                </p>
                            </div>

                            <div class="tracking-timeline">
                                @php
                                    $statuses = [
                                        'new' => ['label' => 'Pesanan Baru', 'icon' => 'fa-shopping-cart'],
                                        'process' => ['label' => 'Diproses', 'icon' => 'fa-cog'],
                                        'delivered' => ['label' => 'Diterima', 'icon' => 'fa-check-circle'],
                                    ];
                                    
                                    $currentStatus = $order->status;
                                    $statusOrder = ['new', 'process', 'delivered'];
                                    $currentIndex = array_search($currentStatus, $statusOrder);
                                    
                                    // Jika status cancel, tampilkan hanya status cancel
                                    $isCancelled = $currentStatus == 'cancel';
                                @endphp

                                @if($isCancelled)
                                    <!-- Tampilan untuk pesanan dibatalkan -->
                                    <div class="timeline-step cancelled">
                                        <div class="step-icon">
                                            <i class="fa fa-times-circle"></i>
                                        </div>
                                        <div class="step-content">
                                            <h6>Dibatalkan</h6>
                                            <p class="text-muted">{{ $order->updated_at->format('d M Y') }} WIB</p>
                                        </div>
                                    </div>
                                @else
                                    <!-- Tampilan normal untuk pesanan aktif -->
                                    @foreach($statuses as $statusKey => $statusData)
                                        @php
                                            $stepIndex = array_search($statusKey, $statusOrder);
                                            $isCompleted = $stepIndex <= $currentIndex;
                                            $isActive = $statusKey == $currentStatus;
                                        @endphp
                                        
                                        <div class="timeline-step {{ $isCompleted ? 'completed' : '' }} {{ $isActive ? 'active' : '' }}">
                                            <div class="step-icon">
                                                @if($isCompleted)
                                                    <i class="fa fa-check"></i>
                                                @else
                                                    <i class="fa {{ $statusData['icon'] }}"></i>
                                                @endif
                                            </div>
                                            <div class="step-content">
                                                <h6>{{ $statusData['label'] }}</h6>
                                                @if($isCompleted)
                                                    <p class="text-muted">
                                                        {{ $order->updated_at->format('d M Y') }} WIB
                                                    </p>
                                                @endif
                                            </div>
                                        </div>

                                        @if(!$loop->last)
                                            <div class="timeline-line {{ $stepIndex < $currentIndex ? 'completed' : '' }}"></div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>

                            <!-- Order Details -->
                            <div class="order-details mt-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Total Pembayaran:</strong> Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                        <p><strong>Metode Pembayaran:</strong> {{ $order->paymentMethod ? $order->paymentMethod->name : 'Tidak tersedia' }}</p>
                                    </div>
                                    <div class="col-md-6">


                                    <div class="order-time-info">
                                        <p class="mb-1">
                                            <strong>Tanggal Order:</strong> 
                                            {{ \Carbon\Carbon::parse($order->created_at)->timezone('Asia/Jakarta')->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                                        </p>
                                        <p class="mb-0">
                                            <strong>Waktu Order:</strong> 
                                                <i class="fas fa-clock me-1"></i>
                                                <span id="order-time-wib">
                                                    {{ \Carbon\Carbon::parse($order->created_at)->timezone('Asia/Jakarta')->format('H:i') }}
                                                </span>WIB
                                        </p>
                                    </div>
                                    <p><strong>Status Pembayaran:</strong> 
                                            <span class="badge badge-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }}">
                                                {{ ucfirst($order->payment_status) }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="col-md-8 form-group">
                        <button type="submit" value="submit" class="btn submit_btn">Lacak Pesanan</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <style>
        .order-tracking-card {
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .order-header {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }

        .order-header h5 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #333;
        }

        .order-status {
            font-size: 14px;
            color: #666;
        }

        .tracking-timeline {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            position: relative;
            margin: 40px 0;
        }
        
        @media (max-width: 768px) {
            .tracking-timeline {
                flex-direction: column;
                align-items: center;
                justify-content: center;
                text-align: center;
                margin: 30px auto;
                max-width: 100%;
            }
            
            .timeline-step {
                width: 100%;
                margin-bottom: 20px;
                position: relative;
                padding: 0 15px;
            }
            
            .step-content {
                width: 100%;
                text-align: center;
            }
            
            .step-icon {
                margin: 0 auto 10px;
            }
            
            .timeline-line {
                display: none;
            }
        }

        .timeline-step {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .step-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            transition: all 0.3s ease;
            border: 4px solid #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .step-icon i {
            font-size: 24px;
            color: #999;
        }

        .timeline-step.completed .step-icon {
            background: #28a745;
        }

        .timeline-step.completed .step-icon i {
            color: #fff;
        }

        .timeline-step.active .step-icon {
            background: #007bff;
            animation: pulse 2s infinite;
        }

        .timeline-step.active .step-icon i {
            color: #fff;
        }

        .timeline-step.cancelled .step-icon {
            background: #dc3545;
            width: 80px;
            height: 80px;
        }

        .timeline-step.cancelled .step-icon i {
            color: #fff;
            font-size: 32px;
        }

        .step-content {
            text-align: center;
        }

        .step-content h6 {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .step-content p {
            font-size: 12px;
            margin: 0;
        }

        .timeline-line {
            position: absolute;
            top: 30px;
            height: 4px;
            background: #e0e0e0;
            z-index: 1;
            left: 0;
            right: 0;
            margin: 0 30px;
        }

        .timeline-line.completed {
            background: #28a745;
        }

        .order-details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
        }

        .order-details p {
            margin-bottom: 10px;
            font-size: 14px;
        }

        .badge {
            padding: 5px 12px;
            font-size: 12px;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(0, 123, 255, 0.7);
            }
            70% {
                box-shadow: 0 0 0 15px rgba(0, 123, 255, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(0, 123, 255, 0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .tracking-timeline {
                flex-direction: column;
            }

            .timeline-line {
                display: none;
            }

            .timeline-step {
                margin-bottom: 30px;
            }
        }
    </style>
@endsection
@push('scripts')
<script>
    
    function updateWIBTime() {
    const now = new Date();
    const options = { 
        timeZone: 'Asia/Jakarta',
        hour12: false,
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    };
    
    const timeString = now.toLocaleTimeString('id-ID', options);
    document.getElementById('realtime-wib').textContent = timeString;
}

// Update setiap detik
updateWIBTime();
setInterval(updateWIBTime, 1000);
</script>
@endpush
