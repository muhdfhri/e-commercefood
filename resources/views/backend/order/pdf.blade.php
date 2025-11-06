<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <title>Invoice #{{ $order->order_number ?? '' }}</title>
  <style type="text/css">
    body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
    .table { width: 100%; margin-bottom: 1rem; color: #212529; border-collapse: collapse; }
    .table th, .table td { padding: 0.75rem; vertical-align: top; border-top: 1px solid #dee2e6; }
    .table thead th { vertical-align: bottom; border-bottom: 2px solid #dee2e6; background-color: #28a745; color: white; }
    .table-bordered { border: 1px solid #dee2e6; }
    .table-bordered th, .table-bordered td { border: 1px solid #dee2e6; }
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .mb-4 { margin-bottom: 1.5rem; }
    .mb-2 { margin-bottom: 0.5rem; }
    .font-weight-bold { font-weight: 700; }
    .text-success { color: #28a745; }
    .w-100 { width: 100%; }
  </style>
</head>
<body>

@if($order)
<style type="text/css">
  .invoice-header {
    background: #f7f7f7;
    padding: 10px 20px 10px 20px;
    border-bottom: 1px solid gray;
  }
  .site-logo {
    margin-top: 20px;
  }
  .invoice-right-top h3 {
    padding-right: 20px;
    margin-top: 20px;
    color: green;
    font-size: 30px!important;
    font-family: serif;
  }
  .invoice-left-top {
    border-left: 4px solid green;
    padding-left: 20px;
    padding-top: 20px;
  }
  .invoice-left-top p {
    margin: 0;
    line-height: 20px;
    font-size: 16px;
    margin-bottom: 3px;
  }
  thead {
    background: green;
    color: #FFF;
  }
  .authority h5 {
    margin-top: -10px;
    color: green;
  }
  .thanks h4 {
    color: green;
    font-size: 25px;
    font-weight: normal;
    font-family: serif;
    margin-top: 20px;
  }
  .site-address p {
    line-height: 6px;
    font-weight: 300;
  }
  .table tfoot .empty {
    border: none;
  }
  .table-bordered {
    border: none;
  }
  .table-header {
    padding: .75rem 1.25rem;
    margin-bottom: 0;
    background-color: rgba(0,0,0,.03);
    border-bottom: 1px solid rgba(0,0,0,.125);
  }
  .table td, .table th {
    padding: .30rem;
  }
</style>
  <div class="invoice-header">
    <div class="float-right site-address">
      <h4>PT PATIN NUSANTARA GLOBALINDO DELI</h4>
      @if($settings->address)
        <p>{{ $settings->address }}</p>
      @endif
      @if($settings->phone)
        <p>No. Telepon: <a href="tel:{{ $settings->phone }}">{{ $settings->phone }}</a></p>
      @endif
      @if($settings->email)
        <p>Email: <a href="mailto:{{ $settings->email }}">{{ $settings->email }}</a></p>
      @endif
    </div>
    <div class="clearfix"></div>
  </div>
  <div class="invoice-description">
    <div class="invoice-left-top float-left">
      <h6>Invoice to</h6>
       <h3>{{$order->first_name}} {{$order->last_name}}</h3>
       <div class="address">
        <p>
          <strong>Negara: </strong>
          {{$order->country}}
        </p>
        <p>
          <strong>Alamat: </strong>
          {{ $order->address1 }} OR {{ $order->address2}}
        </p>
         <p><strong>No. Telepon:</strong> {{ $order->phone }}</p>
         <p><strong>Email:</strong> {{ $order->email }}</p>
       </div>
    </div>
    <div class="invoice-right-top float-right" class="text-right">
      <h3>Invoice #{{$order->order_number}}</h3>
      <p>{{ $order->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</p>
      {{-- <img class="img-responsive" src="data:image/png;base64, {{ base64_encode(QrCode::format('png')->size(150)->generate(route('admin.product.order.show', $order->id )))}}"> --}}
    </div>
    <div class="clearfix"></div>
  </div>
  <section class="order_details pt-3">
    <div class="table-header">
      <h5>Detail Pesanan</h5>
    </div>
    <table class="table table-bordered table-stripe">
      <thead>
        <tr>
          <th scope="col" class="col-6">Produk</th>
          <th scope="col" class="col-3">Jumlah</th>
          <th scope="col" class="col-3">Total</th>
        </tr>
      </thead>
      <tbody>
      @foreach($order->cart_info as $cart)
      @php 
        $product=DB::table('products')->select('title')->where('id',$cart->product_id)->get();
      @endphp
        <tr>
          <td><span>
              @foreach($product as $pro)
                {{$pro->title}}
              @endforeach
            </span></td>
          <td>{{ $cart->quantity }} x</td>
          <td>Rp {{ number_format($cart->price, 0, ',', '.') }}</td>
        </tr>
      @endforeach
      </tbody>
      <tfoot>
        <tr>
          <th scope="col" class="empty"></th>
          <th scope="col" class="text-right">Subtotal:</th>
          <th scope="col"> <span>Rp {{ number_format($order->sub_total, 0, ',', '.') }}</span></th>
        </tr>
      {{-- @if(!empty($order->coupon))
        <tr>
          <th scope="col" class="empty"></th>
          <th scope="col" class="text-right">Diskon:</th>
          <th scope="col"><span>- Rp {{ number_format($order->coupon->discount(Helper::orderPrice($order->id, $order->user->id)), 0, ',', '.') }}</span></th>
        </tr>
      @endif --}}
        <tr>
          <th scope="col" class="empty"></th>
          @php
            $shipping_charge = $order->shipping ? $order->shipping->price : 0;
          @endphp
          <th scope="col" class="text-right">Biaya Pengiriman:</th>
          <th><span>Rp {{ number_format($shipping_charge, 0, ',', '.') }}</span></th>
        </tr>
        <tr>
          <th scope="col" class="empty"></th>
          <th scope="col" class="text-right">Total:</th>
          <th>
            <span>
                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
            </span>
          </th>
        </tr>
      </tfoot>
    </table>
  </section>
  <div class="thanks mt-3">
    <h4>Terima kasih telah berbelanja !!</h4>
  </div>
  <div class="authority float-right mt-5">
    <p>-----------------------------------</p>
    <h5>Tanda Tangan:</h5>
  </div>
  <div class="clearfix"></div>
@else
  <h5 class="text-danger">Pesanan tidak valid</h5>
@endif
</body>
</html>