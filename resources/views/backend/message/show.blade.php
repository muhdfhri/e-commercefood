@extends('backend.layouts.master')
@section('main-content')
<div class="card">
  <h5 class="card-header">Pesan</h5>
  <div class="card-body">
    @if($message)
        @if($message->photo)
        <img src="{{$message->photo}}" class="rounded-circle " style="margin-left:44%;">
        @else 
        <img src="{{asset('backend/img/avatar.png')}}" class="rounded-circle " style="margin-left:44%;">
        @endif
        <div class="py-4">Dari: <br>
           Nama : {{$message->name}}<br>
           Email : {{$message->email}}<br>
           No. Telepon : {{$message->phone}}
        </div>
        <hr/>
  <h5 class="text-center" style="text-decoration:underline"><strong>Subjek :</strong> {{$message->subject}}</h5>
  <h6 class="font-semibold text-gray-700">Isi Pesan:</h6>
  <p class="py-2 text-gray-600">{{$message->message}}</p>
      

    @endif

  </div>
</div>
@endsection