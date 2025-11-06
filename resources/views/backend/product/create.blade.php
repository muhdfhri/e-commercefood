@extends('backend.layouts.master')

@section('main-content')

<div class="card">
    <h5 class="card-header">Tambah Produk</h5>
    <div class="card-body">
      <form method="post" action="{{route('product.store')}}">
        {{csrf_field()}}
        <div class="form-group">
          <label for="inputTitle" class="col-form-label">Judul <span class="text-danger">*</span></label>
          <input id="inputTitle" type="text" name="title" placeholder="Masukkan judul"  value="{{old('title')}}" class="form-control">
          @error('title')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="summary" class="col-form-label">Ringkasan <span class="text-danger">*</span></label>
          <textarea class="form-control" id="summary" name="summary">{{old('summary')}}</textarea>
          @error('summary')
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
          <label for="is_featured">Is Featured</label><br>
          <input type="checkbox" name='is_featured' id='is_featured' value='1' checked> Yes                        
        </div>
              {{-- {{$categories}} --}}

        <div class="form-group">
          <label for="cat_id">Kategori <span class="text-danger">*</span></label>
          <select name="cat_id" id="cat_id" class="form-control">
              <option value="">--Pilih Kategori--</option>
              @foreach($categories as $key=>$cat_data)
                  <option value='{{$cat_data->id}}'>{{$cat_data->title}}</option>
              @endforeach
          </select>
        </div>

        <div class="form-group d-none" id="child_cat_div">
          <label for="child_cat_id">Sub Kategori</label>
          <select name="child_cat_id" id="child_cat_id" class="form-control">
              <option value="">--Pilih Sub Kategori--</option>
              {{-- @foreach($parent_cats as $key=>$parent_cat)
                  <option value='{{$parent_cat->id}}'>{{$parent_cat->title}}</option>
              @endforeach --}}
          </select>
        </div>

        <div class="form-group">
          <label for="price" class="col-form-label">Harga (IDR) <span class="text-danger">*</span></label>
          <input id="price" type="number" name="price" placeholder="Masukkan harga"  value="{{old('price')}}" class="form-control">
          @error('price')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="discount" class="col-form-label">Diskon (%)</label>
          <input id="discount" type="number" name="discount" min="0" max="100" placeholder="Masukkan diskon"  value="{{old('discount', 0)}}" class="form-control">
          <small class="form-text text-muted">Jika tidak ada diskon, isi dengan angka 0</small>
          @error('discount')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label>Varian Produk</label>
          <div class="input-group mb-2">
            <input type="text" id="new-variant" class="form-control" placeholder="Masukkan varian (contoh: Original)">
            <div class="input-group-append">
              <button type="button" id="add-variant" class="btn btn-primary">Tambah</button>
            </div>
          </div>
          
          <!-- Daftar varian yang sudah ditambahkan -->
          <div id="variant-list" class="mb-2">
            <!-- Varian akan ditambahkan di sini -->
          </div>
          
          <!-- Input hidden untuk menyimpan varian -->
          <div id="variant-inputs">
            @if(old('variant'))
              @foreach(old('variant') as $index => $variant)
                <input type="hidden" name="variant[]" value="{{ $variant }}">
              @endforeach
            @endif
          </div>
          
          <small class="text-muted">Ketik varian dan klik tambah untuk menambah ke daftar</small>
          @error('variant')
          <span class="text-danger d-block">{{ $message }}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="brand_id">Brand</label>
          {{-- {{$brands}} --}}

          <select name="brand_id" class="form-control">
              <option value="">--Pilih Brand--</option>
             @foreach($brands as $brand)
              <option value="{{$brand->id}}">{{$brand->title}}</option>
             @endforeach
          </select>
        </div>

        <div class="form-group">
          <label for="condition">Status Produk</label>
          <select name="condition" class="form-control">
              <option value="">--Pilih Status Produk--</option>
              <option value="default">Default</option>
              <option value="new">Baru</option>
              <option value="hot">Unggulan</option>
          </select>
        </div>

        <div class="form-group">
          <label for="stock">Kuantitas <span class="text-danger">*</span></label>
          <input id="quantity" type="number" name="stock" min="0" placeholder="Masukkan Kuantitas"  value="{{old('stock')}}" class="form-control">
          @error('stock')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        <div class="form-group">
          <label for="inputPhoto" class="col-form-label">Foto <span class="text-danger">*</span></label>
          <div class="input-group">
              <span class="input-group-btn">
                  <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                  <i class="fa fa-picture-o"></i> Pilih
                  </a>
              </span>
          <input id="thumbnail" class="form-control" type="text" name="photo" value="{{old('photo')}}">
        </div>
        <div id="holder" style="margin-top:15px;max-height:100px;"></div>
          @error('photo')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        
        <div class="form-group">
          <label for="status" class="col-form-label">Status <span class="text-danger">*</span></label>
          <select name="status" class="form-control">
              <option value="active">Aktif</option>
              <option value="inactive">Non Aktif</option>
          </select>
          @error('status')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        <div class="form-group mb-3">
          <button type="reset" class="btn btn-warning">Reset</button>
           <button class="btn btn-success" type="submit">Simpan</button>
        </div>
      </form>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="{{asset('backend/summernote/summernote.min.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
  .select2-container--default .select2-selection--single {
    height: 38px;
    padding: 5px 10px;
    border: 1px solid #ced4da;
  }
  .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px;
  }
  .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 26px;
  }
</style>
@endpush
@push('scripts')
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script src="{{asset('backend/summernote/summernote.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $('#lfm').filemanager('image');

    $('#summary').summernote({
      placeholder: "Masukkan ringkasan....",
      tabsize: 2,
      height: 100
    });

    $('#description').summernote({
      placeholder: "Masukkan deskripsi.....",
      tabsize: 2,
      height: 150
    });

    // Menangani penambahan varian baru
    $('#add-variant').click(function() {
      const variantInput = $('#new-variant');
      const variantValue = variantInput.val().trim();
      
      if (variantValue) {
        // Tambahkan ke daftar varian yang terlihat
        const variantItem = `
          <div class="badge badge-primary mr-2 mb-2 p-2 d-inline-flex align-items-center">
            ${variantValue}
            <button type="button" class="close ml-2 remove-variant" style="font-size: 1rem;">&times;</button>
          </div>`;
        
        $('#variant-list').append(variantItem);
        
        // Tambahkan input hidden untuk form
        $('#variant-inputs').append(`<input type="hidden" name="variant[]" value="${variantValue}">`);
        
        // Reset input
        variantInput.val('').focus();
      }
    });
    
    // Hapus varian
    $(document).on('click', '.remove-variant', function() {
      $(this).parent().remove();
      // Update input hidden
      updateVariantInputs();
    });
    
    // Fungsi untuk update input hidden
    function updateVariantInputs() {
      const variants = [];
      $('#variant-list .badge').each(function() {
        variants.push($(this).clone().find('.remove-variant').remove().end().text().trim());
      });
      
      $('#variant-inputs').empty();
      variants.forEach(variant => {
        if (variant) {
          $('#variant-inputs').append(`<input type="hidden" name="variant[]" value="${variant}">`);
        }
      });
    }
    
    // Tambahkan varian dengan menekan Enter
    $('#new-variant').keypress(function(e) {
      if (e.which === 13) {
        e.preventDefault();
        $('#add-variant').click();
      }
    });
</script>

<script>
  $('#cat_id').change(function(){
    var cat_id=$(this).val();
    // alert(cat_id);
    if(cat_id !=null){
      // Ajax call
      $.ajax({
        url:"/admin/category/"+cat_id+"/child",
        data:{
          _token:"{{csrf_token()}}",
          id:cat_id
        },
        type:"POST",
        success:function(response){
          if(typeof(response) !='object'){
            response=$.parseJSON(response)
          }
          // console.log(response);
          var html_option="<option value=''>----Pilih sub kategori----</option>"
          if(response.status){
            var data=response.data;
            // alert(data);
            if(response.data){
              $('#child_cat_div').removeClass('d-none');
              $.each(data,function(id,title){
                html_option +="<option value='"+id+"'>"+title+"</option>"
              });
            }
            else{
            }
          }
          else{
            $('#child_cat_div').addClass('d-none');
          }
          $('#child_cat_id').html(html_option);
        }
      });
    }
    else{
    }
  })
</script>
@endpush