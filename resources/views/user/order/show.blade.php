@extends('user.layouts.master')

@section('title','Order Detail')

@section('main-content')
<div class="card">
<h5 class="card-header">Pesanan       <a href="{{route('order.pdf',$order->id)}}" class=" btn btn-sm btn-primary shadow-sm float-right"><i class="fas fa-download fa-sm text-white-50"></i> Generate PDF</a>
  </h5>
  <div class="card-body">
    @if($order)
    <table class="table table-striped table-hover">
      <thead>
        <tr>
            <th>No.</th>
            <th>No. Pesanan</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Kuantitas</th>
            <th>Biaya</th>
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
            <td>Rp {{ number_format($order->shipping->price ?? 0, 0, ',', '.') }}</td>
            <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
            <td>
                @if($order->status=='new')
                  <span class="badge badge-primary">{{$order->status}}</span>
                @elseif($order->status=='process')
                  <span class="badge badge-warning">{{$order->status}}</span>
                @elseif($order->status=='delivered')
                  <span class="badge badge-success">{{$order->status}}</span>
                @elseif($order->status=='complaint')
                  <span class="badge badge-danger">{{$order->status}}</span>
                @else
                  <span class="badge badge-secondary">{{$order->status}}</span>
                @endif
            </td>
            <td>
                <form method="POST" action="{{route('order.destroy',[$order->id])}}">
                  @csrf
                  @method('delete')
                      <button class="btn btn-danger btn-sm dltBtn" data-id={{$order->id}} style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="fas fa-trash-alt"></i></button>
                </form>
            </td>

        </tr>
      </tbody>
    </table>

    <section class="confirmation_part section_padding">
      <div class="order_boxes">
        <div class="row">
          <div class="col-lg-6 col-lx-4">
            <div class="order-info">
              <h4 class="text-center pb-4">INFORMASI PESANAN</h4>
              <table class="table">
                    <tr class="">
                        <td>No. Pesanan</td>
                        <td> : {{$order->order_number}}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Pesanan</td>
                        <td> : {{$order->created_at->format('D d M, Y')}} at {{$order->created_at->format('g : i a')}} </td>
                    </tr>
                    <tr>
                        <td>Kuantitas</td>
                        <td> : {{$order->quantity}}</td>
                    </tr>
                    <tr>
                        <td>Status Pesanan</td>
                        <td> : {{$order->status}}</td>
                    </tr>
                    <tr>
                      @php
                          $shipping_charge=DB::table('shippings')->where('id',$order->shipping_id)->pluck('price');
                      @endphp
                        <td>Biaya Pengiriman</td>
                        <td> : Rp {{ number_format($order->shipping->price ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Total Harga</td>
                        <td> : Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                      <td>Metode Pembayaran</td>
                      <td> : 
                          @if($order->paymentMethod)
                              {{ $order->paymentMethod->name }}
                          @elseif($order->payment_method == 'cod') 
                              Bayar ditempat (COD) 
                          @else 
                              {{ ucfirst($order->payment_method) }}
                          @endif
                      </td>
                    </tr>
                    <tr>
                        <td>Status Pembayaran</td>
                        <td> : {{$order->payment_status}}</td>
                    </tr>
              </table>
            </div>
          </div>

          <div class="col-lg-6 col-lx-4">
            <div class="shipping-info">
              <h4 class="text-center pb-4">INFORMASI PENGIRIMAN</h4>
              <table class="table">
                    <tr class="">
                        <td>Nama Lengkap</td>
                        <td> : {{$order->first_name}} {{$order->last_name}}</td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td> : {{$order->email}}</td>
                    </tr>
                    <tr>
                        <td>No. Telepon</td>
                        <td> : {{$order->phone}}</td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td> : {{$order->address1}}, {{$order->address2}}</td>
                    </tr>
                    <tr>
                        <td>Negara</td>
                        <td> : {{$order->country}}</td>
                    </tr>
                    <tr>
                        <td>Kode Pos</td>
                        <td> : {{$order->post_code}}</td>
                    </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
    @endif

    @if($order && $order->status == 'process')
    <!-- Action Buttons for After-Sales -->
    <div class="mt-4 pb-4 px-4 text-right">
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#receiveOrderModal">
            <i class="fas fa-check-circle"></i> Terima Barang
        </button>
        <button type="button" class="btn btn-warning ml-2" data-toggle="modal" data-target="#complaintModal">
            <i class="fas fa-exclamation-triangle"></i> Ajukan Komplain
        </button>
    </div>

    <!-- Receive Order Modal -->
    <div class="modal fade" id="receiveOrderModal" tabindex="-1" role="dialog" aria-labelledby="receiveOrderModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="receiveOrderModalLabel">Konfirmasi Penerimaan Barang</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="{{ route('user.order.confirm', $order->id) }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="modal-body">
                  <div class="form-group text-left">
                      <label for="proof_of_delivery">Upload Bukti Penerimaan (Foto) <span class="text-danger">*</span></label>
                      <input type="file" class="form-control-file" id="proof_of_delivery" name="proof_of_delivery" accept="image/*" required onchange="previewImage(event)">
                  </div>
                  <div class="mt-2 text-center">
                      <img id="imagePreview" src="#" alt="Preview" style="max-width: 100%; display: none; border-radius: 8px;">
                  </div>
                  <div class="form-group mt-3 text-left">
                      <label for="notes">Catatan (Opsional)</label>
                      <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Tambahkan catatan jika ada..."></textarea>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success">Konfirmasi Selesai</button>
              </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Complaint Modal (Placeholder) -->
    <div class="modal fade" id="complaintModal" tabindex="-1" role="dialog" aria-labelledby="complaintModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="complaintModalLabel">Ajukan Komplain</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="{{ route('user.order.complaint', $order->id) }}" method="POST">
              @csrf
              <div class="modal-body text-left">
                  <div class="form-group">
                      <label for="complaint_reason">Alasan Komplain <span class="text-danger">*</span></label>
                      <textarea class="form-control" id="complaint_reason" name="complaint_reason" rows="4" required></textarea>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-warning">Kirim Komplain</button>
              </div>
          </form>
        </div>
      </div>
    </div>
    @endif

    @if($order && $order->status == 'complaint' && $order->complaints->count() > 0)
    <div class="mt-4 p-4 border rounded bg-light">
        <h4 class="text-danger"><i class="fas fa-exclamation-circle"></i> Detail Komplain</h4>
        <hr>
        <p><strong>Alasan Komplain:</strong></p>
        <blockquote class="blockquote border-left pl-3" style="border-width: 5px !important;">
            {{ $order->complaints->first()->reason }}
        </blockquote>
        <p><strong>Status Komplain:</strong> 
            <span class="badge badge-info">{{ ucfirst($order->complaints->first()->status) }}</span>
        </p>
        <small class="text-muted">Diajukan pada: {{ $order->complaints->first()->created_at->format('d M Y, H:i') }}</small>
    </div>
    @endif

    @if($order && $order->status == 'delivered')
    <div class="mt-5 pt-4 border-top">
        <h4 class="text-center pb-3">Penilaian Produk</h4>
        <div class="row">
            @foreach($order->cart_info as $cart)
            @php
                $hasReviewed = \App\Models\ProductReview::where('product_id', $cart->product_id)->where('order_id', $order->id)->exists();
            @endphp
            <div class="col-md-6 mb-3">
                <div class="card p-3 shadow-sm border-0" style="background: #fafafa; border-radius: 12px;">
                    <div class="d-flex align-items-center">
                        <img src="{{$cart->product->photo}}" alt="{{$cart->product->title}}" style="width:60px; height:60px; object-fit:cover; border-radius:8px;">
                        <div class="ml-3">
                            <h6 class="mb-1 font-weight-bold">{{$cart->product->title}}</h6>
                            @if($hasReviewed)
                                <span class="badge badge-success px-3 py-2"><i class="fas fa-check-circle"></i> Sudah Dinilai</span>
                            @else
                                <button class="btn btn-sm btn-primary mt-1" style="border-radius:20px;" data-toggle="modal" data-target="#reviewModal{{$cart->product_id}}">Beri Penilaian</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if(!$hasReviewed)
            <!-- Review Modal -->
            <div class="modal fade" id="reviewModal{{$cart->product_id}}" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <form action="{{route('review.store',$cart->product->slug)}}" method="POST">
                            @csrf
                            <input type="hidden" name="order_id" value="{{$order->id}}">
                            <div class="modal-header border-0 pb-0">
                                <h5 class="modal-title font-weight-bold">Nilai {{$cart->product->title}}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-center">
                                <div class="rate-stars mb-4 pt-3 pb-2">
                                    <input type="hidden" name="rate" id="rate{{$cart->product_id}}" value="5" required>
                                    <i class="fas fa-star fa-2x star-rating" data-value="1" data-id="{{$cart->product_id}}" style="color:#ffc107; cursor:pointer;"></i>
                                    <i class="fas fa-star fa-2x star-rating" data-value="2" data-id="{{$cart->product_id}}" style="color:#ffc107; cursor:pointer;"></i>
                                    <i class="fas fa-star fa-2x star-rating" data-value="3" data-id="{{$cart->product_id}}" style="color:#ffc107; cursor:pointer;"></i>
                                    <i class="fas fa-star fa-2x star-rating" data-value="4" data-id="{{$cart->product_id}}" style="color:#ffc107; cursor:pointer;"></i>
                                    <i class="fas fa-star fa-2x star-rating" data-value="5" data-id="{{$cart->product_id}}" style="color:#ffc107; cursor:pointer;"></i>
                                </div>
                                <div class="form-group text-left">
                                    <label for="review" class="font-weight-bold">Komentar</label>
                                    <textarea name="review" id="review" rows="4" class="form-control" style="border-radius: 8px;" placeholder="Tuliskan pengalaman Anda..."></textarea>
                                    <small class="text-danger mt-1 d-block"><i class="fas fa-info-circle"></i> Komentar wajib diisi jika rating 3 bintang atau kurang!</small>
                                </div>
                            </div>
                            <div class="modal-footer border-0 pt-0">
                                <button type="button" class="btn btn-light" data-dismiss="modal" style="border-radius:20px;">Batal</button>
                                <button type="submit" class="btn btn-primary" style="border-radius:20px;">Kirim Ulasan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
    @endif

  </div>
</div>
@endsection

@push('scripts')
<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('imagePreview');
            output.src = reader.result;
            output.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    document.querySelectorAll('.star-rating').forEach(star => {
        star.addEventListener('click', function() {
            var val = this.getAttribute('data-value');
            var id = this.getAttribute('data-id');
            document.getElementById('rate' + id).value = val;
            
            var allStars = document.querySelectorAll('.star-rating[data-id="'+id+'"]');
            allStars.forEach(s => {
                if(s.getAttribute('data-value') <= val) {
                    s.style.color = '#ffc107';
                } else {
                    s.style.color = '#e4e5e9';
                }
            });
        });
    });
</script>
@endpush

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
