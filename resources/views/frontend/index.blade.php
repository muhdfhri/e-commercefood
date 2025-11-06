@extends('frontend.layouts.master')
@section('title','Beranda | PT Patin Nusantara GlobalIndo Deli')
@section('main-content')
<!-- Slider Area -->
@if(count($banners)>0)
    <section id="Gslider" class="carousel slide clean-slider" data-ride="carousel">
        <ol class="carousel-indicators">
            @foreach($banners as $key=>$banner)
                <li data-target="#Gslider" data-slide-to="{{$key}}" class="{{(($key==0)? 'active' : '')}}"></li>
            @endforeach
        </ol>
        <div class="carousel-inner" role="listbox">
            @foreach($banners as $key=>$banner)
                <div class="carousel-item {{(($key==0)? 'active' : '')}}">
                    <div class="slider-img">
                        <img class="first-slide" src="{{$banner->photo}}" alt="First slide">
                        <div class="overlay"></div>
                    </div>
                    <div class="carousel-caption d-none d-md-block text-left">
                        <h1 class="wow fadeInDown">{{$banner->title}}</h1>
                        <p>{!! html_entity_decode($banner->description) !!}</p>
                        <a class="btn btn-lg ws-btn wow fadeInUpBig" href="{{route('product-grids')}}" role="button">
                            Belanja Sekarang <i class="far fa-arrow-alt-circle-right"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        <a class="carousel-control-prev" href="#Gslider" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#Gslider" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </section>
@endif
<!--/ End Slider Area -->

<!-- Start Small Banner  -->
<section class="small-banner section">
    <div class="container">
        <div class="row g-4">
            @php
            $category_lists=DB::table('categories')->where('status','active')->limit(3)->get();
            @endphp
            @if($category_lists)
                @foreach($category_lists as $cat)
                    @if($cat->is_parent==1)
                        <!-- Single Banner  -->
                        <div class="col-lg-4 col-md-6 col-12">
                            <div class="single-banner">
                                @if($cat->photo)
                                    <img src="{{$cat->photo}}" alt="{{$cat->photo}}">
                                @else
                                    <img src="https://via.placeholder.com/600x370" alt="#">
                                @endif
                                <div class="banner-overlay"></div>
                                <div class="content">
                                    <h3>{{$cat->title}}</h3>
                                    <p>Jelajahi Produk Kami</p>
                                    <a href="{{route('product-cat',$cat->slug)}}" class="shop-btn">Belanja Sekarang</a>
                                </div>
                            </div>
                        </div>
                    @endif
                    <!-- /End Single Banner  -->
                @endforeach
            @endif
        </div>
    </div>
</section>
<!-- End Small Banner -->

<!-- Start Product Area -->
<div class="product-area section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title">
                        <h2>Kategori Produk</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="product-info">
                        <div class="nav-main">
                            <!-- Tab Nav -->
                            <ul class="nav nav-tabs filter-tope-group" id="myTab" role="tablist">
                                @php
                                    $categories=DB::table('categories')->where('status','active')->where('is_parent',1)->get();
                                    // dd($categories);
                                @endphp
                                @if($categories)
                                <button class="btn" style="background:black"data-filter="*">
                                    Semua Produk
                                </button>
                                    @foreach($categories as $key=>$cat)

                                    <button class="btn" style="background:none;color:black;"data-filter=".{{$cat->id}}">
                                        {{$cat->title}}
                                    </button>
                                    @endforeach
                                @endif
                            </ul>
                            <!--/ End Tab Nav -->
                        </div>
                        <div class="tab-content isotope-grid" id="myTabContent">
                             <!-- Start Single Tab -->
                            @if($product_lists)
                                @foreach($product_lists as $key=>$product)
                                <div class="col-sm-6 col-md-4 col-lg-3 p-b-35 isotope-item {{$product->cat_id}}">
                                    <div class="single-product">
                                        <div class="product-img">
                                            <a href="{{route('product-detail',$product->slug)}}">
                                                @php
                                                    $photo=explode(',',$product->photo);
                                                // dd($photo);
                                                @endphp
                                                <img class="default-img" src="{{$photo[0]}}" alt="{{$photo[0]}}">
                                                <img class="hover-img" src="{{$photo[0]}}" alt="{{$photo[0]}}">
                                                @if($product->stock<=0)
                                                    <span class="out-of-stock">Sale out</span>
                                                @elseif($product->condition=='new')
                                                    <span class="new">Baru</span>
                                                @elseif($product->condition=='hot')
                                                    <span class="hot">Unggulan</span>
                                                @elseif($product->discount > 0)
                                                    <span class="price-dec">{{$product->discount}}% Off</span>
                                                @endif
                                            </a>
                                            <div class="button-head">
                                                <div class="product-action">
                                                    <a title="Wishlist" href="{{route('add-to-wishlist',$product->slug)}}" ><i class=" ti-heart "></i><span>Tambahkan Wishlist</span></a>
                                                </div>
                                                <div class="product-action-2">
                                                    <a title="Add to cart" href="{{route('add-to-cart',$product->slug)}}">Masukkan Keranjang</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-content">
                                            <h3><a href="{{route('product-detail',$product->slug)}}">{{$product->title}}</a></h3>
                                            <div class="product-price">
                                                @php
                                                    $after_discount = $product->price - ($product->price * $product->discount) / 100;
                                                @endphp
                                                <span>Rp{{ number_format($after_discount, 0, ',', '.') }}</span>
                                                @if($product->discount > 0)
                                                    <del style="padding-left:4%;">Rp{{ number_format($product->price, 0, ',', '.') }}</del>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                             <!--/ End Single Tab -->
                            @endif

                        <!--/ End Single Tab -->

                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
<!-- End Product Area -->
@php
    $featured = DB::table('products')
        ->join('categories', 'products.cat_id', '=', 'categories.id')
        ->select('products.*', 'categories.title as category_title')
        ->where('products.is_featured', 1)
        ->where('products.status', 'active')
        ->where('products.discount', '>', 0)
        ->orderBy('products.discount', 'DESC')
        ->limit(2)
        ->get();
@endphp
<!-- Start Midium Banner  -->

<!-- Start Promo Banner -->
<section class="promo-banner">
    <div class="container">
        <div class="promo-header">
            <div class="promo-badge">
                <span class="badge-icon">🔥</span>
                <span>Promo Spesial</span>
            </div>
            <h2 class="promo-title">Diskon Menarik</h2>
            <p class="promo-subtitle">Jangan lewatkan penawaran terbaik untuk produk pilihan kami</p>
        </div>
    </div>  
    <section class="midium-banner">
    <div class="container">
        <div class="row">
            @if($featured && $featured->count() > 0)
                @foreach($featured as $data)
                    <!-- Single Banner  -->
                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="single-banner">
                            @php
                                $photo=explode(',',$data->photo);
                            @endphp
                            <img src="{{$photo[0]}}" alt="{{$photo[0]}}">
                            <div class="content">
                                <p>{{$data->category_title}}</p>
                                <h3>{{$data->title}} <br>Diskon<span> {{$data->discount}}%</span></h3>
                                <a href="{{route('product-detail',$data->slug)}}">Belanja Sekarang</a>
                            </div>
                        </div>
                    </div>
                    <!-- /End Single Banner  -->
                @endforeach
            @endif
        </div>
    </div>
</section>

<style>

/* Promo Header */
.promo-header {
    text-align: center;
    margin-bottom: 40px;
}

.promo-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #dc3545, #e35d6a);
    color: white;
    padding: 8px 20px;
    border-radius: 50px;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 15px;
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

.badge-icon {
    font-size: 1.1rem;
    animation: pulse 2s infinite;
}

.promo-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 10px;
    background: linear-gradient(135deg, #2d3748, #4a5568);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.promo-subtitle {
    font-size: 1.1rem;
    color: #6c757d;
    max-width: 500px;
    margin: 0 auto;
}

/* Single Banner */
.single-banner {
    position: relative;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    transition: all 0.3s ease;
    background: #fff;
    margin-bottom: 30px;
}

.single-banner:hover {
    transform: translateY(-8px);
    box-shadow: 0 16px 40px rgba(0, 0, 0, 0.15);
}

.single-banner img {
    width: 100%;
    height: 300px;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.single-banner:hover img {
    transform: scale(1.05);
}

.promo-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(0,0,0,0.4), rgba(0,0,0,0.1));
    opacity: 0.7;
    transition: opacity 0.3s ease;
}

.single-banner:hover .promo-overlay {
    opacity: 0.5;
}

/* Banner Content */
.single-banner .content {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 30px;
    color: white;
    z-index: 2;
}

.category-badge {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    margin-bottom: 10px;
    display: inline-block;
}

.single-banner .content h3 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 15px;
    line-height: 1.3;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
}

.discount-highlight {
    color: #ffd700;
    font-weight: 800;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8);
}

/* Banner Actions */
.banner-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    flex-wrap: wrap;
}

.btn-banner {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #28a745;
    color: white;
    padding: 12px 24px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

.btn-banner:hover {
    background: #218838;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
    color: white;
}

.price-tag {
    background: rgba(255, 255, 255, 0.9);
    color: #dc3545;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 700;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

/* Promo Footer */
.promo-footer {
    text-align: center;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e9ecef;
}

.promo-note {
    color: #6c757d;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

/* Animations */
@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
    }
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.single-banner {
    animation: slideIn 0.6s ease;
}

/* Responsive Design */
@media (max-width: 768px) {
    .promo-banner {
        padding: 40px 0;
    }
    
    .promo-title {
        font-size: 2rem;
    }
    
    .promo-subtitle {
        font-size: 1rem;
    }
    
    .single-banner .content {
        padding: 20px;
    }
    
    .single-banner .content h3 {
        font-size: 1.3rem;
    }
    
    .banner-actions {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .btn-banner {
        padding: 10px 20px;
        font-size: 0.9rem;
    }
}

@media (max-width: 576px) {
    .promo-title {
        font-size: 1.8rem;
    }
    
    .single-banner img {
        height: 250px;
    }
    
    .single-banner .content {
        padding: 15px;
    }
    
    .single-banner .content h3 {
        font-size: 1.1rem;
    }
}
</style>
<!-- End Midium Banner -->

<!-- Start Most Popular -->
<div class="product-area most-popular section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title">
                    <h2>Produk Unggulan</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="owl-carousel popular-slider">
                    @foreach($product_lists as $product)
                        @if($product->condition=='hot')
                            <!-- Start Single Product -->
                        <div class="single-product">
                            <div class="product-img">
                                <a href="{{route('product-detail',$product->slug)}}">
                                    @php
                                        $photo=explode(',',$product->photo);
                                    // dd($photo);
                                    @endphp
                                    <img class="default-img" src="{{$photo[0]}}" alt="{{$photo[0]}}">
                                    <img class="hover-img" src="{{$photo[0]}}" alt="{{$photo[0]}}">
                                    {{-- <span class="out-of-stock">Hot</span> --}}
                                </a>
                                <div class="button-head">
                                    <div class="product-action">
                                        <a title="Wishlist" href="{{route('add-to-wishlist',$product->slug)}}" ><i class=" ti-heart "></i><span>Tambahkan Wishlist</span></a>
                                    </div>
                                    <div class="product-action-2">
                                        <a href="{{route('add-to-cart',$product->slug)}}">Masukkan Keranjang</a>
                                    </div>
                                </div>
                            </div>
                            <div class="product-content">
                                <h3><a href="{{route('product-detail',$product->slug)}}">{{$product->title}}</a></h3>
                                <div class="product-price">
                                        @php
                                            $after_discount = $product->price - ($product->price * $product->discount) / 100;
                                        @endphp
                                        <span>Rp{{ number_format($after_discount, 0, ',', '.') }}</span>
                                        @if($product->discount > 0)
                                            <del style="padding-left:4%;">Rp{{ number_format($product->price, 0, ',', '.') }}</del>
                                        @endif
                                </div>  
                            </div>
                        </div>
                        <!-- End Single Product -->
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Most Popular Area -->

<!-- Start Shop Services Area -->
<section class="shop-services section home">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-12">
                <!-- Start Single Service -->
                <div class="single-service">
                    <i class="ti-rocket"></i>
                    <h4>Pengiriman Gratis</h4>
                    <p>Untuk pesanan di atas Rp 1.500.000</p>
                </div>
                <!-- End Single Service -->
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <!-- Start Single Service -->
                <div class="single-service">
                    <i class="ti-reload"></i>
                    <h4>Pengembalian Gratis</h4>
                    <p>Dalam waktu 30 hari</p>
                </div>
                <!-- End Single Service -->
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <!-- Start Single Service -->
                <div class="single-service">
                    <i class="ti-lock"></i>
                    <h4>Pembayaran Aman</h4>
                    <p>100% pembayaran terjamin</p>
                </div>
                <!-- End Single Service -->
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <!-- Start Single Service -->
                <div class="single-service">
                    <i class="ti-tag"></i>
                    <h4>Harga Terbaik</h4>
                    <p>Harga terjamin di pasaran</p>
                </div>
                <!-- End Single Service -->
            </div>
        </div>
    </div>
</section>
<!-- End Shop Services Area -->
@endsection

@push('styles')
    <style>
        /* Product Quick View Modal */
        .product-quickview .modal-content {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 30px rgba(0, 0, 0, 0.1);
        }

        .product-quickview .modal-header {
            border: none;
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1;
            padding: 0;
        }

        .product-quickview .close {
            font-size: 24px;
            color: #333;
            opacity: 1;
            text-shadow: none;
            background: rgba(255, 255, 255, 0.8);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .product-quickview .close:hover {
            background: #f8f9fa;
            transform: rotate(90deg);
        }

        .product-quickview .product-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #333;
        }

        .product-quickview .product-gallery {
            position: relative;
            margin-bottom: 1rem;
        }

        .product-quickview .product-main-img {
            border: 1px solid #eee;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 15px;
        }

        .product-quickview .product-main-img img {
            width: 100%;
            height: auto;
            transition: transform 0.3s ease;
        }

        .product-quickview .product-thumbnails {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -5px;
        }

        .product-quickview .thumbnail-item {
            padding: 0 5px;
            margin-bottom: 10px;
        }

        .product-quickview .thumbnail-item img {
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .product-quickview .thumbnail-item img:hover,
        .product-quickview .thumbnail-item.active img {
            border-color: #F7941D;
        }

        .product-quickview .price-section .price {
            font-size: 1.75rem;
            font-weight: 700;
            color: #F7941D;
        }

        .product-quickview .price-section del {
            font-size: 1rem;
            color: #999;
        }

        .product-quickview .badge-danger {
            background-color: #dc3545;
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
        }

        .product-quickview .product-description {
            border-top: 1px solid #eee;
            padding-top: 1rem;
        }

        .product-quickview .description-text {
            max-height: 150px;
            overflow-y: auto;
            padding-right: 10px;
        }

        .product-quickview .description-text::-webkit-scrollbar {
            width: 5px;
        }

        .product-quickview .description-text::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .product-quickview .description-text::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        .product-quickview .input-number {
            width: 80px;
            text-align: center;
            font-weight: 600;
        }

        .product-quickview .btn-number {
            padding: 0.25rem 0.5rem;
            background: #f8f9fa;
            border: 1px solid #ddd;
            color: #333;
        }

        .product-quickview .btn-number:hover {
            background: #e9ecef;
        }

        .product-quickview .btn-primary {
            background-color: #F7941D;
            border-color: #F7941D;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
        }

        .product-quickview .btn-primary:hover {
            background-color: #e68a1a;
            border-color: #e68a1a;
        }

        .product-quickview .wishlist-share {
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid #eee;
        }

        .product-quickview .btn-outline-secondary {
            color: #6c757d;
            border-color: #dee2e6;
        }

        .product-quickview .btn-outline-secondary:hover {
            background-color: #f8f9fa;
            color: #495057;
        }

        /* Responsive adjustments */
        @media (max-width: 991.98px) {
            .product-quickview .modal-dialog {
                max-width: 95%;
                margin: 0.5rem auto;
            }
        }

        @media (max-width: 767.98px) {
            .product-quickview .modal-content {
                padding: 1rem;
            }

            .product-quickview .modal-body {
                padding: 0;
            }

            .product-quickview .row {
                flex-direction: column;
            }

            .product-quickview .col-lg-6 {
                padding: 0;
            }

            .product-quickview .product-details {
                padding: 1.5rem 0 0;
            }

            .product-quickview .price-section .price {
                font-size: 1.5rem;
            }
        }
       /* Clean Slider Styles */
       .clean-slider {
            position: relative;
            margin-bottom: 60px;
        }

        .slider-img {
            position: relative;
            height: 550px;
            overflow: hidden;
        }

        .slider-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to right, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.2));
        }

        .carousel-caption {
            left: 8%;
            right: auto;
            bottom: 50%;
            transform: translateY(50%);
            text-align: left;
            max-width: 600px;
        }

        .carousel-caption h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 15px;
            line-height: 1.3;
        }

        .carousel-caption p {
            font-size: 1.1rem;
            color: #f8f9fa;
            margin-bottom: 25px;
            line-height: 1.6;
        }

        .ws-btn {
            background-color: #ff6b35;
            color: #ffffff;
            padding: 12px 35px;
            border-radius: 5px;
            font-weight: 600;
            border: none;
            transition: all 0.3s;
        }

        .ws-btn:hover {
            background-color: #e85a28;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
        }

        .ws-btn i {
            margin-left: 8px;
        }

        /* Indicators */
        .carousel-indicators li {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.5);
            margin: 0 5px;
        }

        .carousel-indicators li.active {
            background-color: #ff6b35;
            width: 30px;
            border-radius: 5px;
        }

        /* Controls */
        .carousel-control-prev,
        .carousel-control-next {
            width: 50px;
            height: 50px;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            opacity: 0.8;
            transition: all 0.3s;
        }

        .carousel-control-prev:hover,
        .carousel-control-next:hover {
            background-color: rgba(255, 107, 53, 0.8);
            opacity: 1;
        }

        .carousel-control-prev {
            left: 20px;
        }

        .carousel-control-next {
            right: 20px;
        }

        /* Tablet */
        @media (max-width: 991px) {
            .slider-img {
                height: 450px;
            }

            .carousel-caption {
                left: 6%;
                max-width: 500px;
            }

            .carousel-caption h1 {
                font-size: 2rem;
            }

            .carousel-caption p {
                font-size: 1rem;
            }
        }

        /* Mobile */
        @media (max-width: 767px) {
            .clean-slider {
                margin-bottom: 40px;
            }

            .slider-img {
                height: 400px;
            }

            .carousel-caption {
                display: block !important;
                left: 5%;
                right: 5%;
                bottom: 40px;
                transform: none;
                max-width: 100%;
            }

            .carousel-caption h1 {
                font-size: 1.5rem;
                margin-bottom: 10px;
            }

            .carousel-caption p {
                font-size: 0.9rem;
                margin-bottom: 15px;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .ws-btn {
                padding: 10px 25px;
                font-size: 0.9rem;
            }

            .carousel-control-prev,
            .carousel-control-next {
                width: 40px;
                height: 40px;
            }

            .carousel-control-prev {
                left: 10px;
            }

            .carousel-control-next {
                right: 10px;
            }
        }

        /* Small Mobile */
        @media (max-width: 575px) {
            .slider-img {
                height: 350px;
            }

            .carousel-caption h1 {
                font-size: 1.3rem;
            }

            .carousel-caption p {
                font-size: 0.85rem;
            }

            .ws-btn {
                padding: 8px 20px;
                font-size: 0.85rem;
            }
        }

        .small-banner {
        padding: 60px 0;
     
    }

    .single-banner {
        position: relative;
        height: 400px;
        border-radius: 12px;
        overflow: hidden;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .single-banner:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .single-banner img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .single-banner:hover img {
        transform: scale(1.1);
    }

    .banner-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to bottom, rgba(0,0,0,0.2) 0%, rgba(0,0,0,0.7) 100%);
        z-index: 1;
    }

    .single-banner .content {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 30px;
        z-index: 2;
        transform: translateY(0);
        transition: transform 0.3s ease;
    }

    .single-banner .content h3 {
        font-size: 1.8rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 8px;
        text-transform: capitalize;
    }

    .single-banner .content p {
        font-size: 0.95rem;
        color: #f0f0f0;
        margin-bottom: 15px;
        opacity: 0.9;
    }

    .shop-btn {
        display: inline-block;
        background-color: #ff6b35;
        color: #fff;
        padding: 10px 24px;
        border-radius: 6px;
        font-size: 0.9rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        opacity: 0;
        transform: translateY(10px);
    }

    .single-banner:hover .shop-btn {
        opacity: 1;
        transform: translateY(0);
    }

    .shop-btn:hover {
        background-color:rgb(253, 252, 252);
        color: #fff;
        transform: translateY(-2px);
    }

    /* Tablet */
    @media (max-width: 991px) {
        .small-banner {
            padding: 50px 0;
        }

        .single-banner {
            height: 350px;
        }

        .single-banner .content h3 {
            font-size: 1.6rem;
        }

        .single-banner .content {
            padding: 25px;
        }
    }

    /* Mobile */
    @media (max-width: 767px) {
        .small-banner {
            padding: 40px 0;
        }

        .single-banner {
            height: 300px;
            margin-bottom: 20px;
        }

        .single-banner .content {
            padding: 20px;
        }

        .single-banner .content h3 {
            font-size: 1.4rem;
        }

        .single-banner .content p {
            font-size: 0.85rem;
            margin-bottom: 12px;
        }

        .shop-btn {
            padding: 8px 20px;
            font-size: 0.85rem;
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Small Mobile */
    @media (max-width: 575px) {
        .single-banner {
            height: 280px;
        }

        .single-banner .content h3 {
            font-size: 1.3rem;
        }

        .single-banner .content p {
            font-size: 0.8rem;
        }

        .shop-btn {
            padding: 7px 18px;
            font-size: 0.8rem;
        }
    }
    </style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script>
        

        /*==================================================================
        [ Isotope ]*/
        var $topeContainer = $('.isotope-grid');
        var $filter = $('.filter-tope-group');

        // filter items on button click
        $filter.each(function () {
            $filter.on('click', 'button', function () {
                var filterValue = $(this).attr('data-filter');
                $topeContainer.isotope({filter: filterValue});
            });

        });

        // init Isotope
        $(window).on('load', function () {
            var $grid = $topeContainer.each(function () {
                $(this).isotope({
                    itemSelector: '.isotope-item',
                    layoutMode: 'fitRows',
                    percentPosition: true,
                    animationEngine : 'best-available',
                    masonry: {
                        columnWidth: '.isotope-item'
                    }
                });
            });
        });

        var isotopeButton = $('.filter-tope-group button');

        $(isotopeButton).each(function(){
            $(this).on('click', function(){
                for(var i=0; i<isotopeButton.length; i++) {
                    $(isotopeButton[i]).removeClass('how-active1');
                }

                $(this).addClass('how-active1');
            });
        });
    </script>
    <script>
         function cancelFullScreen(el) {
            var requestMethod = el.cancelFullScreen||el.webkitCancelFullScreen||el.mozCancelFullScreen||el.exitFullscreen;
            if (requestMethod) { // cancel full screen.
                requestMethod.call(el);
            } else if (typeof window.ActiveXObject !== "undefined") { // Older IE.
                var wscript = new ActiveXObject("WScript.Shell");
                if (wscript !== null) {
                    wscript.SendKeys("{F11}");
                }
            }
        }

        function requestFullScreen(el) {
            // Supports most browsers and their versions.
            var requestMethod = el.requestFullScreen || el.webkitRequestFullScreen || el.mozRequestFullScreen || el.msRequestFullscreen;

            if (requestMethod) { // Native full screen.
                requestMethod.call(el);
            } else if (typeof window.ActiveXObject !== "undefined") { // Older IE.
                var wscript = new ActiveXObject("WScript.Shell");
                if (wscript !== null) {
                    wscript.SendKeys("{F11}");
                }
            }
            return false
        }
    </script>

@endpush
