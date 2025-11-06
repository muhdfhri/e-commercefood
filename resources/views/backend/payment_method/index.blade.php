@extends('backend.layouts.master')

@section('main-content')
 <!-- DataTales Example -->
 <div class="card shadow mb-4">
     <div class="row">
         <div class="col-md-12">
            @include('backend.layouts.notification')
         </div>
     </div>
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary float-left">Daftar Metode Pembayaran</h6>
      <a href="{{route('payment-methods.create')}}" class="btn btn-primary btn-sm float-right" data-toggle="tooltip" data-placement="bottom" title="Tambah Metode Pembayaran"><i class="fas fa-plus"></i> Tambah Metode</a>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        @if(count($payment_methods)>0)
        <table class="table table-bordered" id="payment-method-dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>No</th>
              <th>Logo</th>
              <th>Nama</th>
              <th>Kode</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($payment_methods as $method)   
                <tr>
                    <td>{{$loop->index+1}}</td>
                    <td>
                        @if($method->image)
                            <img src="{{ asset($method->image) }}" alt="{{ $method->name }}" style="max-width: 50px;">
                        @else
                            <span class="text-muted">Tidak ada gambar</span>
                        @endif
                    </td>
                    <td>{{$method->name}}</td>
                    <td>{{strtoupper($method->code)}}</td>
                    <td>
                        @if($method->status=='active')
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-secondary">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{route('payment-methods.edit',$method->id)}}" class="btn btn-primary btn-sm float-left mr-1" style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" title="edit" data-placement="bottom"><i class="fas fa-edit"></i></a>
                        <form method="POST" action="{{route('payment-methods.destroy',[$method->id])}}">
                            @csrf 
                            @method('delete')
                            <button class="btn btn-danger btn-sm dltBtn" data-id={{$method->id}} style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" data-placement="bottom" title="Hapus"><i class="fas fa-trash-alt"></i></button>
                        </form>
                    </td>
                </tr>  
            @endforeach
          </tbody>
        </table>
        @else
          <h6 class="text-center">Tidak ada data metode pembayaran! Silakan tambahkan metode pembayaran baru.</h6>
        @endif
      </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="delModal" tabindex="-1" role="dialog" aria-labelledby="#delModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="#delModal">Hapus Data</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Apakah Anda yakin ingin menghapus data ini?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <form id="deleteForm" action="" method="POST">
            @csrf
            @method("DELETE")
            <button type="submit" class="btn btn-danger">Hapus</button>
          </form>
        </div>
      </div>
    </div>
</div>
@endsection

@push('styles')
  <link href="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
  <style>
      div.dataTables_wrapper div.dataTables_paginate{
          display: none;
      }
  </style>
@endpush

@push('scripts')
  <!-- Page level plugins -->
  <script src="{{asset('backend/vendor/datatables/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

  <!-- Page level custom scripts -->
  <script>
      $(document).ready(function(){
        $('#payment-method-dataTable').DataTable({
            "columnDefs": [
                {"orderable": false, "targets": [0, 1, 5]}
            ]
        });

        // Sweet alert
        function deleteData(id){
            var id = id;
            var url = '{{route("payment-methods.destroy", "")}}';
            url = url + "/" + id;
            $("#deleteForm").attr('action', url);
        }

        function formSubmit(){
            $("#deleteForm").submit();
        }

        $('.dltBtn').click(function(e){
            e.preventDefault();
            var form = $(this).closest('form');
            var dataID = $(this).data('id');
            
            swal({
                title: "Anda yakin?",
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    form.submit();
                } else {
                    swal("Data Anda aman!");
                }
            });
        });
      });
  </script>
  
  <script>
      $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
      });
  </script>
@endpush
