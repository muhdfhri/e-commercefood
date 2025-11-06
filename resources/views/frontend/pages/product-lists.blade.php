@extends('frontend.layouts.master')

@section('title','Produk | PT Patin Nusantara GlobalIndo Deli')

@section('main-content')
	
		<!-- Breadcrumbs -->
		<div class="breadcrumbs">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<div class="bread-inner">
							<ul class="bread-list">
								<li><a href="{{route('home')}}">Beranda<i class="ti-arrow-right"></i></a></li>
								<li class="active"><a href="{{route('product-grids')}}">Produk</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- End Breadcrumbs -->
		<form action="{{route('shop.filter')}}" method="POST">
		@csrf
			<!-- Product Style 1 -->
			<section class="product-area shop-sidebar shop-list shop section">
				<div class="container">
					<div class="row">
						<div class="col-lg-3 col-md-4 col-12">
							<div class="shop-sidebar">
                                <!-- Single Widget -->
                                <div class="single-widget category">
                                    <h3 class="title">Kategori</h3>
                                    <ul class="categor-list">
										@php
											$menu=App\Models\Category::getAllParentWithChild();
										@endphp
										@if($menu)
										<li>
											@foreach($menu as $cat_info)
													@if($cat_info->child_cat->count()>0)
														<li><a href="{{route('product-cat',$cat_info->slug)}}">{{$cat_info->title}}</a>
															<ul>
																@foreach($cat_info->child_cat as $sub_menu)
																	<li><a href="{{route('product-sub-cat',[$cat_info->slug,$sub_menu->slug])}}">{{$sub_menu->title}}</a></li>
																@endforeach
															</ul>
														</li>
													@else
														<li><a href="{{route('product-cat',$cat_info->slug)}}">{{$cat_info->title}}</a></li>
													@endif
											@endforeach
										</li>
										@endif
                                    </ul>
                                </div>
                                <!--/ End Single Widget -->
                                <!-- Shop By Price -->
								<div class="single-widget range">
									<h3 class="title">Filter Harga</h3>
									<div class="price-filter">
										<div class="price-filter-inner">
											@php
												$max=DB::table('products')->max('price');
											@endphp
											<div id="slider-range" data-min="0" data-max="{{$max}}"></div>
											<div class="product_filter">
											<button type="submit" class="filter_button">Filter</button>
											<div class="label-input">
												<span>Range:</span>
												<input style="" type="text" id="amount" readonly/>
												<input type="hidden" name="price_range" id="price_range" value="@if(!empty($_GET['price'])){{$_GET['price']}}@endif"/>
											</div>
											</div>
										</div>
									</div>
								</div>
								<!--/ End Shop By Price -->
                                <!-- Single Widget -->
                                <div class="single-widget recent-post">
                                    <h3 class="title">Produk Terbaru</h3>
                                    @foreach($recent_products as $product)
                                        <!-- Single Post -->
                                        @php 
                                            $photo=explode(',',$product->photo);
                                        @endphp
                                        <div class="single-post first">
										<div class="image">
                                                <a href="{{route('product-detail', $product->slug)}}">
                                                    <img src="{{$photo[0]}}" alt="{{$product->title}}">
                                                </a>
                                            </div>
                                            <div class="content">
                                                <h5><a href="{{route('product-detail',$product->slug)}}">{{$product->title}}</a></h5>
                                                @php
                                                    $org=($product->price-($product->price*$product->discount)/100);
                                                @endphp
                                                <p class="price"><del class="text-muted">Rp{{number_format($product->price,0,',','.')}}</del>   Rp{{number_format($org,0,',','.')}}  </p>                                                
                                            </div>
                                        </div>
                                        <!-- End Single Post -->
                                    @endforeach
                                </div>
                                <!--/ End Single Widget -->
                                <!-- Single Widget -->
                                <div class="single-widget category">
                                    <h3 class="title">Brand</h3>
                                    <ul class="categor-list">
                                        @php
                                            $brands=DB::table('brands')->orderBy('title','ASC')->where('status','active')->get();
                                        @endphp
                                        @foreach($brands as $brand)
                                            <li><a href="{{route('product-brand',$brand->slug)}}">{{$brand->title}}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                                <!--/ End Single Widget -->
                        	</div>
						</div>
						<div class="col-lg-9 col-md-8 col-12">
							<div class="row">
								<div class="col-12">
									<!-- Shop Top -->
									<div class="shop-top">
										<div class="shop-shorter">
											<div class="single-shorter">
												<label>Show :</label>
												<select class="show" name="show" onchange="this.form.submit();">
													<option value="">Default</option>
													<option value="9" @if(!empty($_GET['show']) && $_GET['show']=='9') selected @endif>09</option>
													<option value="15" @if(!empty($_GET['show']) && $_GET['show']=='15') selected @endif>15</option>
													<option value="21" @if(!empty($_GET['show']) && $_GET['show']=='21') selected @endif>21</option>
													<option value="30" @if(!empty($_GET['show']) && $_GET['show']=='30') selected @endif>30</option>
												</select>
											</div>
											<div class="single-shorter">
												<label>Sort By :</label>
												<select class='sortBy' name='sortBy' onchange="this.form.submit();">
													<option value="">Default</option>
													<option value="title" @if(!empty($_GET['sortBy']) && $_GET['sortBy']=='title') selected @endif>Nama</option>
													<option value="price" @if(!empty($_GET['sortBy']) && $_GET['sortBy']=='price') selected @endif>Harga</option>
													<option value="category" @if(!empty($_GET['sortBy']) && $_GET['sortBy']=='category') selected @endif>Kategori</option>
													<option value="brand" @if(!empty($_GET['sortBy']) && $_GET['sortBy']=='brand') selected @endif>Brand</option>
												</select>
											</div>
										</div>
										<ul class="view-mode">
											<li><a href="{{route('product-grids')}}"><i class="fa fa-th-large"></i></a></li>
											<li class="active"><a href="javascript:void(0)"><i class="fa fa-th-list"></i></a></li>
										</ul>
									</div>
									<!--/ End Shop Top -->
								</div>
							</div>
							<div class="row">
								@if(count($products))
									@foreach($products as $product)
										<!-- Start Single List -->
										<div class="col-12">
											<div class="row">
												<div class="col-lg-4 col-md-6 col-sm-6">
													<div class="single-product">
														<div class="product-img">
															<a href="{{route('product-detail',$product->slug)}}">
															@php 
																$photo=explode(',',$product->photo);
															@endphp
															<img class="default-img" src="{{$photo[0]}}" alt="{{$photo[0]}}">
															<img class="hover-img" src="{{$photo[0]}}" alt="{{$photo[0]}}">
															</a>
															<div class="button-head">
																<div class="product-action">
																	<a data-toggle="modal" data-target="#{{$product->id}}" title="Quick View" href="#"><i class=" ti-eye"></i><span>Quick Shop</span></a>
																	<a title="Wishlist" href="{{route('add-to-wishlist',$product->slug)}}" class="wishlist" data-id="{{$product->id}}"><i class=" ti-heart "></i><span>Add to Wishlist</span></a>
																</div>
																<div class="product-action-2">
																	<a title="Add to cart" href="{{route('add-to-cart',$product->slug)}}">Masukkan Keranjang</a>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-lg-8 col-md-6 col-12">
													<div class="list-content">
														<div class="product-content">
															<div class="product-price">
																@php
																	$after_discount=($product->price-($product->price*$product->discount)/100);
																@endphp
																@if($product->discount > 0)
																	<span class="text-danger">Rp{{ number_format($after_discount, 0, ',', '.') }}</span>
																	<del class="text-muted ml-2">Rp{{ number_format($product->price, 0, ',', '.') }}</del>
																@else
																	<span>Rp{{ number_format($product->price, 0, ',', '.') }}</span>
																@endif
															</div>
															<h3 class="title"><a href="{{route('product-detail',$product->slug)}}">{{$product->title}}</a></h3>
														</div>
														<p class="des pt-2">{!! html_entity_decode($product->summary) !!}</p>
														<button type="button" class="btn btn-primary beli-sekarang" data-slug="{{$product->slug}}">Beli Sekarang!</button>
													</div>
												</div>
											</div>
										</div>
										<!-- End Single List -->
									@endforeach
								@else
									<h4 class="text-warning" style="margin:100px auto;">Tidak ada produk yang ditemukan.</h4>
								@endif
							</div>
							<div class="row">
                            <div class="col-md-12 justify-content-center d-flex">
                                {{-- {{$products->appends($_GET)->links()}} --}}
                            </div>
                          </div>
						</div>
					</div>
				</div>
			</section>
			<!--/ End Product Style 1  -->	
		</form>

		<!-- Modal -->
		@if($products)
			@foreach($products as $key=>$product)
				<div class="modal fade" id="{{$product->id}}" tabindex="-1" role="dialog">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="ti-close" aria-hidden="true"></span></button>
								</div>
								<div class="modal-body">
									<div class="row no-gutters">
										<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
											<!-- Product Slider -->
												<div class="product-gallery">
													<div class="quickview-slider-active">
														@php 
															$photo=explode(',',$product->photo);
														@endphp
														@foreach($photo as $data)
															<div class="single-slider">
																<img src="{{$data}}" alt="{{$data}}">
															</div>
														@endforeach
													</div>
												</div>
											<!-- End Product slider -->
										</div>
										<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
											<div class="quickview-content">
												<h2>{{$product->title}}</h2>
												<div class="quickview-ratting-review">
													<div class="quickview-ratting-wrap">
														<div class="quickview-ratting">
															@php
																$rate=DB::table('product_reviews')->where('product_id',$product->id)->avg('rate');
																$rate_count=DB::table('product_reviews')->where('product_id',$product->id)->count();
															@endphp
															@for($i=1; $i<=5; $i++)
																@if($rate>=$i)
																	<i class="yellow fa fa-star"></i>
																@else 
																<i class="fa fa-star"></i>
																@endif
															@endfor
														</div>
														<a href="#"> ({{$rate_count}} customer review)</a>
													</div>
													<div class="quickview-stock">
														@if($product->stock >0)
														<span><i class="fa fa-check-circle-o"></i> {{$product->stock}} in stock</span>
														@else 
														<span><i class="fa fa-times-circle-o text-danger"></i> {{$product->stock}} out stock</span>
														@endif
													</div>
												</div>
												@php
													$after_discount=($product->price-($product->price*$product->discount)/100);
												@endphp
												<h3><small><del class="text-muted">Rp{{number_format($product->price,0,',','.')}}</del></small>    Rp{{number_format($after_discount,0,',','.')}}  </h3>
												<div class="quickview-peragraph">
													<p>{!! html_entity_decode($product->summary) !!}</p>
												</div>
												@if($product->size)
													<div class="size">
														<h4>Size</h4>
														<ul>
															@php 
																$sizes=explode(',',$product->size);
															@endphp
															@foreach($sizes as $size)
															<li><a href="#" class="one">{{$size}}</a></li>
															@endforeach
														</ul>
													</div>
												@endif
												<form action="{{route('single-add-to-cart')}}" method="POST">
													@csrf 
													<div class="quantity">
														<!-- Input Order -->
														<div class="input-group">
															<div class="button minus">
																<button type="button" class="btn btn-primary btn-number" disabled="disabled" data-type="minus" data-field="quant[1]">
																	<i class="ti-minus"></i>
																</button>
															</div>
															<input type="hidden" name="slug" value="{{$product->slug}}">
															<input type="text" name="quant[1]" class="input-number"  data-min="1" data-max="1000" value="1">
															<div class="button plus">
																<button type="button" class="btn btn-primary btn-number" data-type="plus" data-field="quant[1]">
																	<i class="ti-plus"></i>
																</button>
															</div>
														</div>
														<!--/ End Input Order -->
													</div>
													<div class="add-to-cart">
														<button type="submit" class="btn">Masukkan Keranjang</button>
														<a href="{{route('add-to-wishlist',$product->slug)}}" class="btn min"><i class="ti-heart"></i></a>
													</div>
												</form>
												<div class="default-social">
													<!-- ShareThis BEGIN --><div class="sharethis-inline-share-buttons"></div><!-- ShareThis END -->
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
				</div>
			@endforeach
		@endif
		<!-- Modal end -->
@endsection

@push('styles')
<style>
	.pagination{
        display:inline-flex;
    }
	.filter_button{
        text-align: center;
        background:#F7941D;
        padding:8px 16px;
        margin-top:10px;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    .filter_button:hover{
        background:#e6891a;
    }
    .beli-sekarang {
        background: #28a745;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
        transition: background 0.3s;
    }
    .beli-sekarang:hover {
        background: #218838;
    }
    .beli-sekarang:disabled {
        background: #6c757d;
        cursor: not-allowed;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- Script untuk Beli Sekarang -->
<script>
      $(document).ready(function() {
        // Handle tombol Beli Sekarang
        $('.beli-sekarang').on('click', function(e) {
            e.preventDefault();
            
            const $button = $(this);
            const productSlug = $button.data('slug');
            
            // Simpan teks asli dan tampilkan loading
            const originalText = $button.html();
            $button.html('<i class="fa fa-spinner fa-spin"></i> Memproses...');
            $button.prop('disabled', true);
            
            console.log('Memproses beli sekarang untuk produk:', productSlug);
            
            // Method 1: Direct redirect (lebih reliable)
            window.location.href = '{{ url("/add-to-cart") }}/' + productSlug + '?redirect=cart';
            
            // Fallback: Jika redirect tidak bekerja dalam 2 detik
            setTimeout(function() {
                $button.html(originalText).prop('disabled', false);
            }, 2000);
        });
        
        // Price range slider
        if ($("#slider-range").length > 0) {
            const max_value = parseInt($("#slider-range").data('max')) || 500;
            const min_value = parseInt($("#slider-range").data('min')) || 0;
            let price_range = min_value + '-' + max_value;
            
            if($("#price_range").length > 0 && $("#price_range").val()){
                price_range = $("#price_range").val().trim();
            }
            
            let price = price_range.split('-');
            $("#slider-range").slider({
                range: true,
                min: min_value,
                max: max_value,
                values: price,
                slide: function (event, ui) {
                    $("#amount").val('Rp' + ui.values[0].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " - Rp" + ui.values[1].toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
                    $("#price_range").val(ui.values[0] + "-" + ui.values[1]);
                }
            });
            
            if ($("#amount").length > 0) {
                $("#amount").val('Rp' + $("#slider-range").slider("values", 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + " - Rp" + $("#slider-range").slider("values", 1).toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
            }
        }
    });
</script>
@endpush