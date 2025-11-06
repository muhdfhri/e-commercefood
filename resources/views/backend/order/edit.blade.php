@extends('backend.layouts.master')

@section('title','Order Edit')

@section('main-content')
<div class="card">
  <h5 class="card-header">Edit Pesanan</h5>
  <div class="card-body">
    <form action="{{route('order.update',$order->id)}}" method="POST">
      @csrf
      @method('PATCH')
      <div class="form-group">
        <label for="status">Status Pesanan :</label>
        <select name="status" id="status" class="form-control">
          <option value="new" {{($order->status=='delivered' || $order->status=="process" || $order->status=="cancel") ? 'disabled' : ''}}  {{(($order->status=='new')? 'selected' : '')}}>Baru</option>
          <option value="process" {{($order->status=='delivered'|| $order->status=="cancel") ? 'disabled' : ''}}  {{(($order->status=='process')? 'selected' : '')}}>Diproses/Dikirim</option>
          <option value="delivered" {{($order->status=="cancel") ? 'disabled' : ''}}  {{(($order->status=='delivered')? 'selected' : '')}}>Diterima</option>
          <option value="cancel" {{($order->status=='delivered') ? 'disabled' : ''}}  {{(($order->status=='cancel')? 'selected' : '')}}>Dibatalkan</option>
        </select>
      </div>
      
      <div class="form-group">
        <label for="payment_status">Status Pembayaran :</label>
        <select name="payment_status" id="payment_status" class="form-control">
          <option value="unpaid" {{($order->payment_status=='unpaid') ? 'selected' : ''}}>Belum Dibayar</option>
          <option value="paid" {{($order->payment_status=='paid') ? 'selected' : ''}}>Sudah Dibayar</option>
        </select>
      </div>
      
      <div class="form-group">
        <label for="payment_method_id">Metode Pembayaran :</label>
        <select name="payment_method_id" id="payment_method_id" class="form-control" required>
          <option value="">-- Pilih Metode Pembayaran --</option>
          @foreach($paymentMethods as $method)
            <option value="{{ $method->id }}" {{ ($order->payment_method_id == $method->id) ? 'selected' : '' }}>
              {{ $method->name }}
            </option>
          @endforeach
        </select>
        @error('payment_method_id')
          <span class="text-danger">{{ $message }}</span>
        @enderror
      </div>
      
      <div class="text-left">
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('order.index') }}" class="btn btn-secondary">Kembali</a>
      </div>
    </form>
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
