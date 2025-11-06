@extends('backend.layouts.master')

@section('title','Order Detail')

@section('main-content')
<div class="card">
<h5 class="card-header">Order       <a href="{{route('order.pdf',$order->id)}}" class=" btn btn-sm btn-primary shadow-sm float-right"><i class="fas fa-download fa-sm text-white-50"></i> Generate PDF</a>
  </h5>
  <div class="card-body">
    @if($order)
    <table class="table table-striped table-hover">
      <thead>
        <tr>
            <th>No</th>
            <th>No. Pesanan</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Jumlah</th>
            <th>Biaya Pengiriman</th>
            <th>Total Harga</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <tr>
            <td>{{$order->id}}</td>
            <td>{{$order->order_number}}</td>
            <td>{{$order->first_name}} {{$order->last_name}}</td>
            <td>{{$order->email}}</td>
            <td>{{$order->quantity}}</td>
            <td>Rp {{ $order->shipping ? number_format($order->shipping->price, 0, ',', '.') : '0' }}</td>
            <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
            <td>
                @if($order->status=='new')
                  <span class="badge badge-primary">{{$order->status}}</span>
                @elseif($order->status=='process')
                  <span class="badge badge-warning">{{$order->status}}</span>
                @elseif($order->status=='delivered')
                  <span class="badge badge-success">{{$order->status}}</span>
                @else
                  <span class="badge badge-danger">{{$order->status}}</span>
                @endif
            </td>
            <td>
                <a href="{{route('order.edit',$order->id)}}" class="btn btn-primary btn-sm float-left mr-1" style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" title="Ubah" data-placement="bottom"><i class="fas fa-edit"></i></a>
                <form method="POST" action="{{route('order.destroy',[$order->id])}}">
                  @csrf
                  @method('delete')
                      <button class="btn btn-danger btn-sm dltBtn" data-id={{$order->id}} style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" data-placement="bottom" title="Hapus"><i class="fas fa-trash-alt"></i></button>
                </form>
            </td>

        </tr>
      </tbody>
    </table>

        <section class="confirmation_part section_padding">
        <div class="order_boxes">
            <div class="row">
                <!-- Informasi Pesanan -->
                <div class="col-lg-6 col-xl-6 mb-4 mb-lg-0">
                    <div class="order-info card h-100 shadow-sm">
                        <div class="card-header bg-primary text-white text-center py-3">
                            <h4 class="mb-0">INFORMASI PESANAN</h4>
                        </div>
                        <div class="card-body p-4">
                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="fw-semibold text-muted">No. Pesanan</td>
                                            <td class="text-end">: {{$order->order_number}}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold text-muted">Tanggal Order</td>
                                            <td class="text-end">: {{ \Carbon\Carbon::parse($order->created_at)->locale('id')->isoFormat('dddd, D MMMM Y') }} Jam {{ $order->created_at->format('H:i') }} WIB</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold text-muted">Jumlah</td>
                                            <td class="text-end">: {{$order->quantity}}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold text-muted">Status Order</td>
                                            <td class="text-end">: 
                                                <span class="badge bg-info">{{$order->status}}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold text-muted">Biaya Pengiriman</td>
                                            <td class="text-end">: Rp {{ $order->shipping ? number_format($order->shipping->price, 0, ',', '.') : '0' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold text-muted">Kupon</td>
                                            <td class="text-end">: Rp {{ number_format($order->coupon, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold text-muted">Total Harga</td>
                                            <td class="text-end fw-bold text-primary">: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold text-muted">Metode Pembayaran</td>
                                            <td class="text-end">: 
                                                @if($order->paymentMethod)
                                                    {{ $order->paymentMethod->name }}
                                                    @if($order->paymentMethod->image)
                                                        <img src="{{ asset($order->paymentMethod->image) }}" alt="{{ $order->paymentMethod->name }}" class="ms-2" style="max-height: 20px;">
                                                    @endif
                                                @else
                                                    Tidak ada metode pembayaran
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold text-muted">Status Pembayaran</td>
                                            <td class="text-end">: 
                                                @if($order->payment_status == 'paid')
                                                    <span class="badge bg-success">Lunas</span>
                                                @elseif($order->payment_status == 'unpaid')
                                                    <span class="badge bg-warning text-dark">Belum Lunas</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($order->payment_status) }}</span>
                                                @endif
                                            </td>
                                        </tr>

                                        @if($order->payment_proof)
                                        <tr>
                                            <td class="fw-semibold text-muted">Bukti Pembayaran</td>
                                            <td class="text-end">: 
                                                <div class="btn-group btn-group-sm mt-1">
                                                    <a href="{{ asset($order->payment_proof) }}" target="_blank" class="btn btn-outline-primary">
                                                        <i class="fas fa-eye me-1"></i> Lihat
                                                    </a>
                                                    @if(file_exists(public_path($order->payment_proof)))
                                                        <a href="{{ route('order.download-proof', $order->id) }}" class="btn btn-outline-success">
                                                            <i class="fas fa-download me-1"></i> Download
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endif

                                        @if($order->paymentMethod && $order->paymentMethod->description)
                                        <tr>
                                            <td class="fw-semibold text-muted">Keterangan</td>
                                            <td class="text-end">: {!! nl2br(e($order->paymentMethod->description)) !!}</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Pengiriman -->
                <div class="col-lg-6 col-xl-6">
                    <div class="shipping-info card h-100 shadow-sm">
                        <div class="card-header bg-success text-white text-center py-3">
                            <h4 class="mb-0">INFORMASI PENGIRIMAN</h4>
                        </div>
                        <div class="card-body p-4">
                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="fw-semibold text-muted">Nama Lengkap</td>
                                            <td class="text-end">: {{$order->first_name}} {{$order->last_name}}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold text-muted">Email</td>
                                            <td class="text-end">: {{$order->email}}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold text-muted">No. Telepon</td>
                                            <td class="text-end">: {{$order->phone}}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold text-muted">Alamat</td>
                                            <td class="text-end">: {{$order->address1}}, {{$order->address2}}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold text-muted">Negara</td>
                                            <td class="text-end">: {{$order->country}}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold text-muted">Kode Pos</td>
                                            <td class="text-end">: {{$order->post_code}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Daftar Produk yang Dipesan -->
    <section class="ordered-products mt-5">
      <div class="card">
        <h5 class="card-header">Daftar Produk Dipesan</h5>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Gambar</th>
                  <th>Produk</th>
                  <th>Varian</th>
                  <th>Harga</th>
                  <th>Jumlah</th>
                  <th>Subtotal</th>
                </tr>
              </thead>
              <tbody>
                @if($order->cart->count() > 0)
                  @foreach($order->cart as $key => $item)
                    @php
                      $product = $item->product;
                    @endphp
                    <tr>
                      <td>{{ $key + 1 }}</td>
                      <td>
                        @if($product && $product->photo)
                          <img src="{{ asset($product->photo) }}" alt="{{ $product->title }}" style="max-width: 80px; max-height: 80px;">
                        @else
                          <img src="{{ asset('backend/img/thumbnail-default.jpg') }}" alt="default-image" style="max-width: 80px; max-height: 80px;">
                        @endif
                      </td>
                      <td>
                        @if($product)
                          {{ $product->title }}
                        @else
                          Produk tidak ditemukan
                        @endif
                      </td>
                      <td>
                        @if(!empty($item->variant) && $item->variant !== 'null')
                          {{ $item->variant }}
                        @else
                          @php
                            // Debug info
                            // {{-- dd($item->toArray()) --}}
                          @endphp
                          -
                        @endif
                      </td>
                      <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                      <td>{{ $item->quantity }}</td>
                      <td>Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                    </tr>
                  @endforeach
                @else
                  <tr>
                    <td colspan="7" class="text-center">Tidak ada data produk</td>
                  </tr>
                @endif
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="5"></th>
                  <th>Total:</th>
                  <th>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </section>
    @endif

  </div>
</div>
@endsection

@push('styles')
<style>
    .order-info,.shipping-info{
        background:#ECECEC;
        padding:20px;
    }
    .order-info h4,.shipping-info h4{
        text-decoration: underline;
    }

</style>
@endpush
