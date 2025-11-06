@extends('frontend.layouts.master')

@section('title','Tentang | PT. Patin Nusantara Globalindo')

@section('main-content')

	<!-- Breadcrumbs -->
	<div class="breadcrumbs">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="bread-inner">
						<ul class="bread-list">
							<li><a href="{{route('home')}}">Beranda<i class="ti-arrow-right"></i></a></li>
							<li class="active"><a href="{{route('about-us')}}">Tentang</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End Breadcrumbs -->

	<!-- About Us Hero -->
	<section class="about-hero section">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-lg-6 col-12">
					<div class="about-content">
						@php
							$settings=DB::table('settings')->get();
						@endphp
						<h3>Selamat Datang di <span>PT. Patin Nusantara Globalindo</span></h3>
						<p>@foreach($settings as $data) {{$data->description}} @endforeach</p>
						<div class="button">
							<a href="{{route('contact')}}" class="btn primary">Hubungi Kami</a>
						</div>
					</div>
				</div>
				<div class="col-lg-6 col-12">
					<div class="about-img-wrapper">
						@if(isset($settings[0]->photo))
							<img src="{{$settings[0]->photo}}" alt="Tentang Kami">
							<div class="cta-overlay">
								<div class="cta-content">
									<h4>Ayo Sekarang Giliran Kamu!</h4>
									<a href="{{route('product-grids')}}" class="btn-cta">Rasakan Kualitas Terbaik</a>
								</div>
							</div>
						@else
							<div class="img-placeholder">
								<i class="fa fa-image"></i>
								<p>Gambar Tidak Tersedia</p>
							</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- End About Us Hero -->
    
	<!-- About Details Section -->
	<section class="about-details section">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-lg-6 col-12">
					<div class="details-content">
						<p class="lead">Mitra terpercaya dalam menghadirkan produk olahan ikan berkualitas tinggi.</p>
						<p>Kami adalah perusahaan yang berkomitmen memberikan layanan olahan ikan berkualitas tinggi dengan pendekatan yang profesional dan efisien. Dengan pengalaman dan keahlian tim kami, setiap permasalahan olahan ikan yang Anda hadapi akan ditangani dengan solusi terbaik.</p>
						
						<div class="stats-grid">
						<div class="stat-box">
							<h3>100+</h3>
							<p>Pelanggan Puas</p>
						</div>
						<div class="stat-box">
							<h3>5+</h3>
							<p>Tahun Pengalaman</p>
						</div>
						<div class="stat-box">
							<h3>10+</h3>
							<p>Produk Olahan Ikan</p>
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-6 col-12">
				<div class="features-list">
					<div class="feature-item">
						<div class="feature-icon">
						<i class="fa fa-cutlery"></i>
						</div>
						<div class="feature-text">
							<h4>Produk Berkualitas</h4>
							<p>Setiap produk diolah dari bahan baku ikan segar pilihan dengan proses higienis dan standar mutu tinggi.</p>
						</div>
					</div>

					<div class="feature-item">
						<div class="feature-icon">
							<i class="fa fa-leaf"></i>
						</div>
						<div class="feature-text">
							<h4>Inovasi Rasa</h4>
							<p>Kami terus berinovasi menghadirkan cita rasa khas Nusantara dalam berbagai varian yang digemari konsumen.</p>
						</div>
					</div>

					<div class="feature-item">
						<div class="feature-icon">
							<i class="fa fa-handshake-o"></i>
						</div>
						<div class="feature-text">
							<h4>Komitmen dan Kepercayaan</h4>
							<p>Kepercayaan pelanggan adalah prioritas kami — melalui pelayanan ramah, pengiriman tepat waktu, dan kualitas terbaik.</p>
						</div>
					</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- End About Details Section -->

<style>
/* About Hero Section */
.about-hero {
	padding: 60px 0;
	background: #ffffff;
}

.about-hero .about-content h3 {
	font-size: 32px;
	font-weight: 700;
	color: #1a1a1a;
	margin-bottom: 20px;
	line-height: 1.4;
}

.about-hero .about-content h3 span {
	color: #F7941D;
}

.about-hero .about-content p {
	font-size: 16px;
	color: #666;
	line-height: 1.8;
	margin-bottom: 30px;
}

.about-hero .button .btn.primary {
	background: #F7941D;
	color: #fff;
	padding: 12px 32px;
	border-radius: 4px;
	font-weight: 600;
	font-size: 14px;
	text-transform: uppercase;
	letter-spacing: 0.5px;
	transition: all 0.3s ease;
	border: none;
	display: inline-block;
}

.about-hero .button .btn.primary:hover {
	background: #d97f19;
	transform: translateY(-2px);
	box-shadow: 0 4px 12px rgba(247, 148, 29, 0.3);
}

/* About Image Wrapper */
.about-img-wrapper {
	position: relative;
	border-radius: 8px;
	overflow: hidden;
	box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.about-img-wrapper img {
	width: 100%;
	height: auto;
	display: block;
	transition: transform 0.4s ease;
}

.about-img-wrapper:hover img {
	transform: scale(1.05);
}

/* CTA Overlay */
.cta-overlay {
	position: absolute;
	bottom: 0;
	left: 0;
	right: 0;
	background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
	padding: 40px 30px 30px;
	opacity: 0;
	transition: opacity 0.3s ease;
}

.about-img-wrapper:hover .cta-overlay {
	opacity: 1;
}

.cta-content {
	text-align: center;
}

.cta-content h4 {
	color: #fff;
	font-size: 18px;
	font-weight: 600;
	margin-bottom: 15px;
}

.cta-content .btn-cta {
	background: #F7941D;
	color: #fff;
	padding: 10px 24px;
	border-radius: 4px;
	font-weight: 600;
	font-size: 13px;
	text-transform: uppercase;
	text-decoration: none;
	display: inline-block;
	transition: all 0.3s ease;
}

.cta-content .btn-cta:hover {
	background: #fff;
	color: #F7941D;
	transform: translateY(-2px);
}

/* Image Placeholder */
.img-placeholder {
	background: #f5f5f5;
	padding: 100px 20px;
	text-align: center;
	color: #999;
	border-radius: 8px;
}

.img-placeholder i {
	font-size: 48px;
	margin-bottom: 15px;
	display: block;
}

.img-placeholder p {
	margin: 0;
	font-size: 14px;
}

/* About Details Section */
.about-details {
	padding: 80px 0;
	background: #f8f9fa;
}

.details-content .lead {
	font-size: 20px;
	font-weight: 500;
	color: #1a1a1a;
	margin-bottom: 20px;
	line-height: 1.6;
}

.details-content p {
	font-size: 16px;
	color: #666;
	line-height: 1.8;
	margin-bottom: 40px;
}

/* Stats Grid */
.stats-grid {
	display: grid;
	grid-template-columns: repeat(3, 1fr);
	gap: 20px;
}

.stat-box {
	background: #fff;
	padding: 30px 20px;
	border-radius: 6px;
	text-align: center;
	box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
	transition: all 0.3s ease;
}

.stat-box:hover {
	transform: translateY(-4px);
	box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
}

.stat-box h3 {
	font-size: 36px;
	font-weight: 700;
	color: #F7941D;
	margin-bottom: 8px;
}

.stat-box p {
	font-size: 14px;
	color: #666;
	margin: 0;
	font-weight: 500;
}

/* Features List */
.features-list {
	display: flex;
	flex-direction: column;
	gap: 20px;
}

.feature-item {
	display: flex;
	gap: 20px;
	background: #fff;
	padding: 24px;
	border-radius: 6px;
	box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
	transition: all 0.3s ease;
}

.feature-item:hover {
	transform: translateX(8px);
	box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.feature-icon {
	flex-shrink: 0;
	width: 56px;
	height: 56px;
	background: #fff4e6;
	border-radius: 6px;
	display: flex;
	align-items: center;
	justify-content: center;
}

.feature-icon i {
	font-size: 24px;
	color: #F7941D;
}

.feature-text h4 {
	font-size: 18px;
	font-weight: 600;
	color: #1a1a1a;
	margin-bottom: 8px;
}

.feature-text p {
	font-size: 14px;
	color: #666;
	margin: 0;
	line-height: 1.6;
}

/* Responsive Design */
@media (max-width: 991px) {
	.about-hero {
		padding: 40px 0;
	}
	
	.about-img-wrapper {
		margin-top: 40px;
	}
	
	.about-details {
		padding: 60px 0;
	}
	
	.features-list {
		margin-top: 40px;
	}
}

@media (max-width: 767px) {
	.about-hero .about-content h3 {
		font-size: 26px;
	}
	
	.stats-grid {
		grid-template-columns: 1fr;
		gap: 15px;
	}
	
	.stat-box {
		padding: 24px 20px;
	}
	
	.stat-box h3 {
		font-size: 28px;
	}
	
	.feature-item {
		padding: 20px;
	}
	
	.feature-icon {
		width: 48px;
		height: 48px;
	}
	
	.feature-icon i {
		font-size: 20px;
	}
	
	.details-content .lead {
		font-size: 18px;
	}
}
</style>

@endsection