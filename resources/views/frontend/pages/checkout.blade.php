@extends('frontend.layouts.master')

@section('title','Checkout | PT Patin Nusantara GlobalIndo Deli')

@section('main-content')

    <!-- Breadcrumbs -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread-inner">
                        <ul class="bread-list">
                            <li><a href="{{route('home')}}">Home<i class="ti-arrow-right"></i></a></li>
                            <li class="active"><a href="javascript:void(0)">Checkout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumbs -->
            
    <!-- Start Checkout -->
    <section class="shop checkout section">
        <div class="container">
                <form class="form" method="post" action="{{route('cart.order')}}" enctype="multipart/form-data" id="checkoutForm">
                    @csrf
                    <div class="row"> 

                        <div class="col-lg-8 col-12">
                            <div class="checkout-form">
                                <h2>Lakukan Pembayaran Anda di Sini</h2>
                                <p>Silahkan isi form di bawah ini untuk melakukan checkout</p>
                                <!-- Form -->
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Nama Depan<span>*</span></label>
                                            <input type="text" name="first_name" id="first_name" placeholder="" value="{{old('first_name')}}">
                                            <span class="error-message" id="error_first_name"></span>
                                            @error('first_name')
                                                <span class='text-danger'>{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Nama Belakang<span>*</span></label>
                                            <input type="text" name="last_name" id="last_name" placeholder="" value="{{old('last_name')}}">
                                            <span class="error-message" id="error_last_name"></span>
                                            @error('last_name')
                                                <span class='text-danger'>{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Email<span>*</span></label>
                                            <input type="email" name="email" id="email" placeholder="" value="{{old('email')}}">
                                            <span class="error-message" id="error_email"></span>
                                            @error('email')
                                                <span class='text-danger'>{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label>No. Telepon<span>*</span></label>
                                            <input type="number" name="phone" id="phone" placeholder="" value="{{old('phone')}}">
                                            <span class="error-message" id="error_phone"></span>
                                            @error('phone')
                                                <span class='text-danger'>{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="country">Negara <span>*</span></label>
                                        <select name="country" id="country" required>
                                            <option value="">-- Pilih Negara --</option>
                                            <option value="Indonesia" selected>Indonesia</option>
                                            <option value="Malaysia">Malaysia</option>
                                            <option value="Singapore">Singapore</option>
                                            <!-- tambahkan negara lainnya -->
                                        </select>
                                        <span class="error-message text-danger" id="error_country"></span>
                                    </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Alamat<span>*</span></label>
                                            <input type="text" name="address1" id="address1" placeholder="" value="{{old('address1')}}">
                                            <span class="error-message" id="error_address1"></span>
                                            @error('address1')
                                                <span class='text-danger'>{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Alamat Lainnya (Opsional)</label>
                                            <input type="text" name="address2" id="address2" placeholder="" value="{{old('address2')}}">
                                            @error('address2')
                                                <span class='text-danger'>{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Kode Pos<span>*</span></label>
                                            <input type="text" name="post_code" id="post_code" placeholder="" value="{{old('post_code')}}">
                                            <span class="error-message" id="error_post_code"></span>
                                            @error('post_code')
                                                <span class='text-danger'>{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                </div>
                                <!--/ End Form -->
                            </div>
                        </div>
                        <div class="col-lg-4 col-12">
                            <div class="order-details">
                                <!-- Order Widget -->
                                <div class="single-widget">
                                    <h2>Total</h2>
                                    <div class="content">
                                        <ul>
										    <li class="order_subtotal" data-price="{{Helper::totalCartPrice()}}">Subtotal<span>Rp {{number_format(Helper::totalCartPrice(), 0, ',', '.')}}</span></li>
                                            <li class="shipping">
                                                Biaya Pengiriman
                                                @if(count(Helper::shipping())>0 && Helper::cartCount()>0)
                                                    <select name="shipping" id="shipping_method" class="nice-select">
                                                        <option value="">Pilih Tipe Pengiriman</option>
                                                        @foreach(Helper::shipping() as $shipping)
                                                        <option value="{{$shipping->id}}" class="shippingOption" data-price="{{$shipping->price}}">{{$shipping->type}}: Rp {{number_format($shipping->price, 0, ',', '.')}}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="error-message" id="error_shipping"></span>
                                                @else 
                                                    <span>Free</span>
                                                @endif
                                            </li>
                                            
                                            @if(session('coupon'))
                                            <li class="coupon_price" data-price="{{session('coupon')['value']}}">Potongan Harga<span>Rp {{number_format(session('coupon')['value'], 0, ',', '.')}}</span></li>
                                            @endif
                                            @php
                                                $total_amount=Helper::totalCartPrice();
                                                if(session('coupon')){
                                                    $total_amount=$total_amount-session('coupon')['value'];
                                                }
                                            @endphp
                                            @if(session('coupon'))
                                                <li class="last"  id="order_total_price">Total<span>Rp {{number_format($total_amount, 0, ',', '.')}}</span></li>
                                            @else
                                                <li class="last"  id="order_total_price">Total<span>Rp {{number_format($total_amount, 0, ',', '.')}}</span></li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                                <!--/ End Order Widget -->
                                <!-- Order Widget -->
                                <div class="single-widget">
                                    <h2>Pembayaran</h2>
                                    <div class="content">
                                        <div class="payment-methods">
                                            @foreach($paymentMethods as $method)
                                                <div class="payment-method-item">
                                                    <label class="payment-method-label">
                                                        <input name="payment_method" type="radio" value="{{ $method->code }}" class="payment-method-radio" required>
                                                        <span class="payment-method-name">{{ $method->name }}</span>
                                                    </label>
                                                    <div class="payment-method-details" style="display: none;">
                                                        @if($method->image)
                                                            <div class="payment-method-image">
                                                                <img src="{{ asset($method->image) }}" alt="{{ $method->name }}">
                                                            </div>
                                                        @endif
                                                        @if($method->description)
                                                            <div class="payment-method-description">
                                                                {!! nl2br(e($method->description)) !!}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                            <span class="error-message text-danger" id="error_payment"></span>
                                        </div>
                                        
                                        <!-- Payment Proof Upload Section -->
                                            <div id="payment-proof-section" style="display: none;">
                                                <div class="payment-proof-container">
                                                    <div class="form-group">
                                                        <label for="payment_proof" class="payment-proof-label">
                                                            Upload Bukti Pembayaran <span class="text-danger">*</span>
                                                        </label>
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" id="payment_proof" name="payment_proof" accept="image/*,.pdf" required>
                                                            <label class="custom-file-label" for="payment_proof">Pilih file...</label>
                                                        </div>
                                                        <small class="form-text text-muted payment-proof-hint">
                                                            <i class="fa fa-info-circle"></i> Mohon unggah bukti transfer/pembayaran Anda. Format yang didukung: JPG, PNG, PDF (maks 2MB)
                                                        </small>
                                                        <span class="error-message text-danger" id="error_payment_proof"></span>
                                                        @error('payment_proof')
                                                            <span class="text-danger error-message">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            @push('styles')
                                            <style>
                                                #payment-proof-section {
                                                    margin-top: 20px;
                                                    padding: 10px 30px;
                                                    animation: slideDown 0.3s ease-out;
                                                }
                                                
                                                .payment-proof-container {
                                                    background: #f9f9f9;
                                                    border-radius: 4px;
                                                    border-left: 3px solid #F7941D;
                                                    padding: 20px;
                                                }
                                                
                                                .payment-proof-label {
                                                    font-weight: 600;
                                                    color: #333;
                                                    margin-bottom: 10px;
                                                    font-size: 15px;
                                                    display: block;
                                                }
                                                
                                                .custom-file {
                                                    margin-bottom: 15px;
                                                }
                                                
                                                .custom-file-input:focus ~ .custom-file-label {
                                                    border-color: #F7941D;
                                                    box-shadow: 0 0 0 0.2rem rgba(247, 148, 29, 0.25);
                                                }
                                                
                                                .custom-file-label {
                                                    border: 2px dashed #ddd;
                                                    background-color: #fff;
                                                    padding: 12px 15px;
                                                    cursor: pointer;
                                                    transition: all 0.3s ease;
                                                    border-radius: 4px;
                                                }
                                                
                                                .custom-file-label:hover {
                                                    border-color: #F7941D;
                                                    background-color: #fff9f0;
                                                }
                                                
                                                .custom-file-label::after {
                                                    content: "Browse";
                                                    background-color: #F7941D;
                                                    border-color: #F7941D;
                                                    color: #fff;
                                                    padding: 8px 20px;
                                                    border-radius: 3px;
                                                    transition: background-color 0.3s ease;
                                                }
                                                
                                                .custom-file-label:hover::after {
                                                    background-color: #e5831a;
                                                }
                                                
                                                .payment-proof-hint {
                                                    display: block;
                                                    margin-top: 12px;
                                                    padding-top: 8px;
                                                    font-size: 13px;
                                                    line-height: 1.6;
                                                    color: #666;
                                                }
                                                
                                                .payment-proof-hint i {
                                                    color: #F7941D;
                                                    margin-right: 5px;
                                                }
                                                
                                                .error-message {
                                                    display: block;
                                                    margin-top: 10px;
                                                    font-size: 14px;
                                                }
                                                
                                                @keyframes slideDown {
                                                    from {
                                                        opacity: 0;
                                                        transform: translateY(-10px);
                                                    }
                                                    to {
                                                        opacity: 1;
                                                        transform: translateY(0);
                                                    }
                                                }
                                            </style>
                                            @endpush

                                            
                                    </div>
                                </div>

                                @push('styles')
                                <style>
                                    .payment-methods {
                                    padding: 10px 30px;
                                }
                                    .payment-method-item {
                                        margin-bottom: 15px;
                                        padding-bottom: 15px;
                                        border-bottom: 1px solid #eee;
                                    }
                                    .payment-method-item:last-child {
                                        border-bottom: none;
                                        padding-bottom: 0;
                                    }
                                    .payment-method-label {
                                        display: flex;
                                        align-items: center;
                                        cursor: pointer;
                                        font-weight: 500;
                                        padding: 10px;
                                        transition: background-color 0.2s;
                                        border-radius: 4px;
                                    }
                                    .payment-method-label:hover {
                                        background-color: #f5f5f5;
                                    }
                                    .payment-method-radio {
                                        margin-right: 12px;
                                        cursor: pointer;
                                        width: 18px;
                                        height: 18px;
                                    }
                                    .payment-method-name {
                                        font-weight: 600;
                                        flex-grow: 1;
                                        color: #333;
                                    }
                                    .payment-method-details {
                                        margin-top: 12px;
                                        padding: 15px;
                                        background: #f9f9f9;
                                        border-radius: 4px;
                                        border-left: 3px solid #F7941D;
                                        animation: slideDown 0.3s ease-out;
                                    }
                                    @keyframes slideDown {
                                        from {
                                            opacity: 0;
                                            transform: translateY(-10px);
                                        }
                                        to {
                                            opacity: 1;
                                            transform: translateY(0);
                                        }
                                    }
                                    .payment-method-image {
                                        margin-bottom: 12px;
                                        text-align: center;
                                    }
                                    .payment-method-image img {
                                        max-height: 100px;
                                        max-width: 200px;
                                        object-fit: contain;
                                        border-radius: 4px;
                                    }
                                    .payment-method-description {
                                        font-size: 14px;
                                        line-height: 1.6;
                                        color: #555;
                                    }
                                    .error-message {
                                        display: block;
                                        margin-top: 10px;
                                        font-size: 14px;
                                    }
                                </style>
                                @endpush

                        
                                <!--/ End Order Widget -->

                                <!-- Button Widget -->
                                <div class="single-widget get-button">
                                    <div class="content">
                                        <div class="button">
                                            <button type="button" class="btn" id="submitCheckoutBtn">Lanjutkan</button>
                                        </div>
                                    </div>
                                </div>
                                <!--/ End Button Widget -->
                            </div>
                        </div>
                    </div>
                </form>
        </div>
    </section>
    <!--/ End Checkout -->
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
		
		/* Styling untuk error message */
		.error-message {
			display: block;
			color: #dc3545;
			font-size: 13px;
			margin-top: 5px;
			font-weight: 500;
		}
		
		/* Input error state */
		input.error, select.error {
			border: 1px solid #dc3545 !important;
			background-color: #fff5f5 !important;
		}
		
		/* Nice select error state */
		.nice-select.error {
			border: 1px solid #dc3545 !important;
			background-color: #fff5f5 !important;
		}
	</style>
@endpush
@push('scripts')
<script src="{{asset('frontend/js/nice-select/js/jquery.nice-select.min.js')}}"></script>
<script src="{{ asset('frontend/js/select2/js/select2.min.js') }}"></script>
<script>
    $(document).ready(function() { 
        $("select.select2").select2(); 
    });
    $('select.nice-select').niceSelect();
</script>

<script>
    function showMe(box){
        var checkbox=document.getElementById('shipping').style.display;
        var vis= 'none';
        if(checkbox=="none"){
            vis='block';
        }
        if(checkbox=="block"){
            vis="none";
        }
        document.getElementById(box).style.display=vis;
    }
</script>

<script>
    $(document).ready(function(){
        // ============================================
        // SHIPPING COST CALCULATION
        // ============================================
        $('.shipping select[name=shipping]').change(function(){
            let cost = parseFloat( $(this).find('option:selected').data('price') ) || 0;
            let subtotal = parseFloat( $('.order_subtotal').data('price') ); 
            let coupon = parseFloat( $('.coupon_price').data('price') ) || 0; 
            let total = subtotal + cost - coupon;
            $('#order_total_price span').text('Rp ' + total.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, "."));
        });

        // ============================================
        // PAYMENT METHOD & PAYMENT PROOF HANDLING
        // ============================================
        // Hide all payment method details initially
        $('.payment-method-details').hide();
        
        // Payment method radio change event
        $('.payment-method-radio').on('change', function() {
            // Hide all payment method details
            $('.payment-method-details').hide();
            
            // Show selected payment method details
            const details = $(this).closest('.payment-method-item').find('.payment-method-details');
            if (details.find('.payment-method-image, .payment-method-description').length > 0) {
                details.show();
            }
            
            // Show/hide payment proof upload based on payment method
            const paymentMethod = $(this).val().toLowerCase();
            const paymentProofSection = $('#payment-proof-section');
            const paymentProofInput = $('#payment_proof');
            
            if (paymentMethod !== 'cod' && paymentMethod !== 'bayar di tempat') {
                paymentProofSection.slideDown(300);
                paymentProofInput.prop('required', true);
            } else {
                paymentProofSection.slideUp(300);
                paymentProofInput.prop('required', false);
                paymentProofInput.val(''); // Reset file input
                $('.custom-file-label').text('Pilih file...'); // Reset label
            }
            
            // Clear payment error
            $('#error_payment').text('');
        });

        // ============================================
        // FILE INPUT HANDLING
        // ============================================
        $('#payment_proof').on('change', function(e) {
            const fileName = e.target.files[0]?.name || 'Pilih file...';
            $(this).next('.custom-file-label').text(fileName);
            
            // Clear error message when file is selected
            $('#error_payment_proof').text('');
        });

        // ============================================
        // FORM VALIDATION - Clear errors on input
        // ============================================
        $('input, select, textarea').on('input change', function(){
            $(this).removeClass('error');
            let fieldId = $(this).attr('id');
            if(fieldId) {
                $('#error_' + fieldId).text('');
            }
        });
        
        // Clear error for payment method radio
        $('input[name="payment_method"]').on('change', function(){
            $('#error_payment').text('');
        });
        
        // Clear error for shipping
        $('select[name="shipping"]').on('change', function(){
            $('#error_shipping').text('');
        });

        // ============================================
        // SUBMIT BUTTON HANDLER (Button diluar form)
        // ============================================
        $('#submitCheckoutBtn').on('click', function(e){
            e.preventDefault();
            
            // Trigger form validation dan submission
            $('#checkoutForm').trigger('submit');
        });

        // ============================================
        // FORM SUBMISSION & VALIDATION (BERURUTAN)
        // ============================================
        $('#checkoutForm').on('submit', function(e){
            e.preventDefault();
            
            // Reset all errors
            $('.error-message').text('');
            $('input, select, textarea').removeClass('error');
            $('.nice-select').removeClass('error');
            
            let isValid = true;
            let firstError = null;
            
            // ==========================================
            // SECTION 1: VALIDASI DATA DIRI
            // ==========================================
            
            // Validasi Nama Depan
            if(!$('#first_name').val().trim()){
                $('#first_name').addClass('error');
                $('#error_first_name').text('Nama depan harus diisi');
                if(!firstError) firstError = $('#first_name');
                isValid = false;
            }
            
            // Validasi Nama Belakang
            if(!$('#last_name').val().trim()){
                $('#last_name').addClass('error');
                $('#error_last_name').text('Nama belakang harus diisi');
                if(!firstError) firstError = $('#last_name');
                isValid = false;
            }
            
            // Validasi Email
            let email = $('#email').val().trim();
            if(!email){
                $('#email').addClass('error');
                $('#error_email').text('Email harus diisi');
                if(!firstError) firstError = $('#email');
                isValid = false;
            } else {
                let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if(!emailPattern.test(email)){
                    $('#email').addClass('error');
                    $('#error_email').text('Format email tidak valid');
                    if(!firstError) firstError = $('#email');
                    isValid = false;
                }
            }
            
            // Validasi No. Telepon
            if(!$('#phone').val().trim()){
                $('#phone').addClass('error');
                $('#error_phone').text('No. telepon harus diisi');
                if(!firstError) firstError = $('#phone');
                isValid = false;
            }
            
            // Validasi Negara (jika ada field country)
            if($('#country').length && !$('#country').val()){
                $('#country').addClass('error');
                $('#error_country').text('Negara harus dipilih');
                if(!firstError) firstError = $('#country');
                isValid = false;
            }
            
            // Validasi Alamat
            if(!$('#address1').val().trim()){
                $('#address1').addClass('error');
                $('#error_address1').text('Alamat harus diisi');
                if(!firstError) firstError = $('#address1');
                isValid = false;
            }
            
            // Validasi Provinsi
            if($('#province').length && !$('#province').val()){
                $('#province').addClass('error');
                $('#province').siblings('.nice-select').addClass('error');
                $('#error_province').text('Provinsi harus dipilih');
                if(!firstError) firstError = $('#province');
                isValid = false;
            }
            
            // Validasi Kota (jika ada)
            if($('#city').length && !$('#city').val().trim()){
                $('#city').addClass('error');
                $('#error_city').text('Kota harus diisi');
                if(!firstError) firstError = $('#city');
                isValid = false;
            }
            
            // Validasi Kode Pos
            let postCode = $('#post_code').val().trim();
            if(!postCode){
                $('#post_code').addClass('error');
                $('#error_post_code').text('Kode pos harus diisi');
                if(!firstError) firstError = $('#post_code');
                isValid = false;
            } else {
                // Validasi hanya angka
                let postCodePattern = /^[0-9]+$/;
                if(!postCodePattern.test(postCode)){
                    $('#post_code').addClass('error');
                    $('#error_post_code').text('Kode pos hanya boleh berisi angka');
                    if(!firstError) firstError = $('#post_code');
                    isValid = false;
                } 
                // Validasi panjang kode pos (Indonesia: 5 digit)
                else if(postCode.length !== 5){
                    $('#post_code').addClass('error');
                    $('#error_post_code').text('Kode pos harus 5 digit');
                    if(!firstError) firstError = $('#post_code');
                    isValid = false;
                }
            }
            
            // ==========================================
            // SECTION 2: VALIDASI BIAYA PENGIRIMAN
            // ==========================================
            @if(count(Helper::shipping())>0 && Helper::cartCount()>0)
            if(!$('select[name="shipping"]').val()){
                $('select[name="shipping"]').addClass('error');
                $('#error_shipping').text('Metode pengiriman harus dipilih');
                if(!firstError) firstError = $('select[name="shipping"]');
                isValid = false;
            }
            @endif
            
            // ==========================================
            // SECTION 3: VALIDASI METODE PEMBAYARAN
            // ==========================================
            if(!$('input[name="payment_method"]:checked').val()){
                $('#error_payment').text('Metode pembayaran harus dipilih');
                if(!firstError) firstError = $('.payment-method-radio').first().closest('.payment-method-item');
                isValid = false;
            }
            
            // ==========================================
            // SECTION 4: VALIDASI BUKTI PEMBAYARAN
            // ==========================================
            const paymentProofInput = $('#payment_proof');
            if(paymentProofInput.length && paymentProofInput.prop('required') && !paymentProofInput.val()){
                paymentProofInput.addClass('error');
                $('#error_payment_proof').text('Bukti pembayaran harus diupload');
                if(!firstError) firstError = paymentProofInput;
                isValid = false;
            }
            
            // Jika ada error, scroll ke error pertama
            if(!isValid){
                if(firstError){
                    $('html, body').animate({
                        scrollTop: firstError.offset().top - 100
                    }, 500);
                    
                    // Focus ke element jika bisa di-focus
                    if(firstError.is('input, select, textarea')){
                        firstError.focus();
                    }
                }
                return false;
            }
            
            // Jika semua valid, submit form
            this.submit();
        });
    });
</script>
@endpush