@extends('backend.layouts.master')

@section('main-content')
<div class="card">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Tambah Metode Pembayaran</h6>
    </div>
    <div class="card-body">
        <form method="post" action="{{route('payment-methods.store')}}" enctype="multipart/form-data">
            {{csrf_field()}}
            
            <div class="form-group">
                <label for="name" class="col-form-label">Nama Metode <span class="text-danger">*</span></label>
                <input id="name" type="text" name="name" placeholder="Masukkan nama metode" value="{{old('name')}}" class="form-control" required>
                @error('name')
                <span class="text-danger">{{$message}}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="code" class="col-form-label">Kode <span class="text-danger">*</span></label>
                <input id="code" type="text" name="code" placeholder="Contoh: bank_transfer" value="{{old('code')}}" class="form-control" required>
                @error('code')
                <span class="text-danger">{{$message}}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="description" class="col-form-label">Deskripsi</label>
                <textarea class="form-control" id="description" name="description">{{old('description')}}</textarea>
                @error('description')
                <span class="text-danger">{{$message}}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="image" class="col-form-label">Logo/Gambar</label>
                <div class="input-group">
                    <span class="input-group-btn">
                        <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                        <i class="fa fa-picture-o"></i> Pilih Gambar
                        </a>
                    </span>
                    <input id="thumbnail" class="form-control" type="text" name="image" value="{{old('image')}}">
                </div>
                <div id="holder" style="margin-top:15px;max-height:100px;"></div>
                @error('image')
                <span class="text-danger">{{$message}}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="status" class="col-form-label">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-control">
                    <option value="active">Aktif</option>
                    <option value="inactive">Nonaktif</option>
                </select>
                @error('status')
                <span class="text-danger">{{$message}}</span>
                @enderror
            </div>
            
            <div class="form-group mb-3">
                <button type="reset" class="btn btn-warning">Reset</button>
                <button class="btn btn-primary" type="submit">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{asset('backend/summernote/summernote.min.css')}}">
@endpush

@push('scripts')
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script src="{{asset('backend/summernote/summernote.min.js')}}"></script>

<script>
    $('#lfm').filemanager('image');

    $(document).ready(function() {
        $('#description').summernote({
            placeholder: "Tulis deskripsi singkat.....",
            tabsize: 2,
            height: 100
        });
    });
</script>
@endpush
