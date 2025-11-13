@extends('frontend.layouts.master')
@section('title','Keranjang | PT Patin Nusantara GlobalIndo Deli')
@section('main-content')
	<!-- Breadcrumbs -->
	<div class="breadcrumbs">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="bread-inner">
						<ul class="bread-list">
							<li><a href="{{route('home')}}">Beranda<i class="ti-arrow-right"></i></a></li>
							<li class="active"><a href="{{route('cart')}}">Keranjang</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End Breadcrumbs -->

	<!-- Shopping Cart -->
	<div class="shopping-cart section">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<!-- Shopping Summery -->
					<table class="table shopping-summery">
						<thead>
							<tr class="main-hading">
								<th>PRODUK</th>
								<th>NAMA</th>
								<th class="text-center">HARGA SATUAN</th>
								<th class="text-center">JUMLAH</th>
								<th class="text-center">TOTAL</th>
								<th class="text-center"><i class="ti-trash remove-icon"></i></th>
							</tr>
						</thead>
						<tbody id="cart_item_list">
							@auth
							<form action="{{route('cart.update')}}" method="POST">
								@csrf
								@if(Helper::getAllProductFromCart())
									@php
										$carts = Helper::getAllProductFromCart();
										$cartCount = $carts ? count($carts) : 0;
									@endphp

									@if($cartCount > 0)
										@foreach($carts as $key => $cart)
											<tr>
												@php
												$photo = explode(',', $cart->product['photo']);
												@endphp
												<td class="image" data-title="No"><img src="{{ $photo[0] }}" alt="{{ $photo[0] }}"></td>
												<td class="product-des" data-title="Description">
													<p class="product-name"><a href="{{ route('product-detail', $cart->product['slug']) }}" target="_blank">{{ $cart->product['title'] }}</a></p>
													<p class="product-des">{!! $cart['summary'] !!}</p>
												</td>
												<td class="price" data-title="Price"><span>Rp {{ number_format($cart['price'], 0, ',', '.') }}</span></td>
												<td class="qty" data-title="Qty">
													<div class="input-group">
														<div class="button minus">
															<button type="button" class="btn btn-primary btn-number" disabled="disabled" data-type="minus" data-field="quant[{{ $key }}]">
																<i class="ti-minus"></i>
															</button>
														</div>
														<input type="text" name="quant[{{ $key }}]" class="input-number" data-min="1" data-max="100" value="{{ $cart->quantity }}">
														<input type="hidden" name="qty_id[]" value="{{ $cart->id }}">
														<div class="button plus">
															<button type="button" class="btn btn-primary btn-number" data-type="plus" data-field="quant[{{ $key }}]">
																<i class="ti-plus"></i>
															</button>
														</div>
													</div>
												</td>
												<td class="total-amount cart_single_price" data-title="Total"><span class="money">Rp {{ number_format($cart['amount'], 0, ',', '.') }}</span></td>
												<td class="action" data-title="Remove">
													<a href="{{ route('cart-delete', $cart->id) }}" class="text-danger">
														<i class="ti-trash remove-icon"></i>
													</a>
												</td>
											</tr>
										@endforeach
										<tr>
											<td colspan="6" class="text-right">
												<button class="btn btn-primary" type="submit">Perbarui Keranjang</button>
											</td>
										</tr>
									@else
										<tr>
											<td colspan="6" class="text-center py-4">
												<div class="empty-cart-message">
													<i class="ti-shopping-cart" style="font-size: 48px; color: #ddd; margin-bottom: 15px;"></i>
													<h4>Keranjang Belanja Kosong</h4>
													<p>Anda belum menambahkan produk ke keranjang belanja.</p>
												</div>
											</td>
										</tr>
									@endif
								@endif
							</form>
							@else
								<tr>
									<td colspan="6" class="text-center py-4">
										<div class="empty-cart-message">
											<i class="ti-shopping-cart" style="font-size: 48px; color: #ddd; margin-bottom: 15px;"></i>
											<h4>Anda Belum Login</h4>
											<p>Silakan login terlebih dahulu untuk melihat keranjang belanja Anda.</p>
											<a href="{{ route('login.form') }}" class="btn btn-primary">Login</a>
											<a href="{{ route('register.form') }}" class="btn btn-outline-primary">Daftar</a>
										</div>
									</td>
								</tr>
							@endauth
						</tbody>
					</table>
					<!--/ End Shopping Summery -->
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<!-- Total Amount -->
					<div class="total-amount">
						<div class="row">
							<div class="col-lg-8 col-md-5 col-12">
								<div class="left">
									<div class="coupon">
									<form action="{{route('coupon-store')}}" method="POST">
											@csrf
											<input name="code" placeholder="Masukkan Kode Kupon">
											<button class="btn">Terapkan</button>
										</form>
									</div>
									{{-- <div class="checkbox">`
										@php
											$shipping=DB::table('shippings')->where('status','active')->limit(1)->get();
										@endphp
										<label class="checkbox-inline" for="2"><input name="news" id="2" type="checkbox" onchange="showMe('shipping');"> Shipping</label>
									</div> --}}
								</div>
							</div>
							<div class="col-lg-4 col-md-7 col-12">
								<div class="right">
									<ul>
										<li class="order_subtotal" data-price="{{Helper::totalCartPrice()}}">Subtotal<span>Rp {{number_format(Helper::totalCartPrice(),2)}}</span></li>

										@php
											$subtotal = Helper::totalCartPrice();
											$coupon = session('coupon');
											$discount = 0;
											$total_amount = $subtotal;

											// Hitung diskon hanya jika ada kupon dan subtotal > 0
											if ($coupon && $subtotal > 0) {
												$discount = $coupon['value'];
												$total_amount = max(0, $subtotal - $discount);
											}
										@endphp

										@if($coupon && $subtotal > 0)
											<li class="coupon_price" data-price="{{ $discount }}">
												Potongan Harga<span>Rp {{ number_format($discount, 2) }}</span>
											</li>
										@endif

										<li class="last" id="order_total_price">
											Total<span>Rp {{ number_format($total_amount, 2) }}</span>
										</li>
									</ul>
									<div class="button5">
										<a href="{{route('checkout')}}" class="btn">Checkout</a>
										<a href="{{route('product-grids')}}" class="btn">Lanjutkan berbelanja</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!--/ End Total Amount -->
				</div>
			</div>
		</div>
	</div>
	<!--/ End Shopping Cart -->

@endsection
@push('styles')
	<style>
		li.shipping{
			display: inline-flex;
			width: 100%;
			font-size: 14px;
		}
		li.shipping .input-group-icon {
			width: 100%;
			margin-left: 10px;
		}
		.input-group-icon .icon {
			position: absolute;
			left: 20px;
			top: 0;
			line-height: 40px;
			z-index: 3;
		}
		.form-select {
			height: 30px;
			width: 100%;
		}
		.form-select .nice-select {
			border: none;
			border-radius: 0px;
			height: 40px;
			background: #f6f6f6 !important;
			padding-left: 45px;
			padding-right: 40px;
			width: 100%;
		}
		.list li{
			margin-bottom:0 !important;
		}
		.list li:hover{
			background:#F7941D !important;
			color:white !important;
		}
		.form-select .nice-select::after {
			top: 14px;
		}
	</style>
@endpush
@push('scripts')
	<script src="{{asset('frontend/js/nice-select/js/jquery.nice-select.min.js')}}"></script>
	<script src="{{ asset('frontend/js/select2/js/select2.min.js') }}"></script>
	<script>
		$(document).ready(function() { $("select.select2").select2(); });
  		$('select.nice-select').niceSelect();
	</script>
	<script>
		$(document).ready(function(){
			$('.shipping select[name=shipping]').change(function(){
				let cost = parseFloat( $(this).find('option:selected').data('price') ) || 0;
				let subtotal = parseFloat( $('.order_subtotal').data('price') );
				let coupon = parseFloat( $('.coupon_price').data('price') ) || 0;
				// alert(coupon);
				$('#order_total_price span').text('$'+(subtotal + cost-coupon).toFixed(2));
			});

		});

	</script>

@endpush
