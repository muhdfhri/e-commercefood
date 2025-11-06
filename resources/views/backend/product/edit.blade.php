@extends('backend.layouts.master')

@section('main-content')

<div class="card">
    <h5 class="card-header">Edit Product</h5>
    <div class="card-body">
      <form method="post" action="{{route('product.update',$product->id)}}">
        @csrf 
        @method('PATCH')
        <div class="form-group">
          <label for="inputTitle" class="col-form-label">Judul <span class="text-danger">*</span></label>
          <input id="inputTitle" type="text" name="title" placeholder="Masukkan judul"  value="{{$product->title}}" class="form-control">
          @error('title')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="summary" class="col-form-label">Ringkasan <span class="text-danger">*</span></label>
          <textarea class="form-control" id="summary" name="summary">{{$product->summary}}</textarea>
          @error('summary')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="description" class="col-form-label">Deskripsi</label>
          <textarea class="form-control" id="description" name="description">{{$product->description}}</textarea>
          @error('description')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>


        <div class="form-group">
          <label for="is_featured">Is Featured</label><br>
          <input type="checkbox" name='is_featured' id='is_featured' value='{{$product->is_featured}}' {{(($product->is_featured) ? 'checked' : '')}}> Yes                        
        </div>
              {{-- {{$categories}} --}}

        <div class="form-group">
          <label for="cat_id">Kategori <span class="text-danger">*</span></label>
          <select name="cat_id" id="cat_id" class="form-control">
              <option value="">--Pilih kategori--</option>
              @foreach($categories as $key=>$cat_data)
                  <option value='{{$cat_data->id}}' {{(($product->cat_id==$cat_data->id)? 'selected' : '')}}>{{$cat_data->title}}</option>
              @endforeach
          </select>
        </div>
        @php 
          $sub_cat_info=DB::table('categories')->select('title')->where('id',$product->child_cat_id)->get();
        // dd($sub_cat_info);

        @endphp
        {{-- {{$product->child_cat_id}} --}}
        <div class="form-group {{(($product->child_cat_id)? '' : 'd-none')}}" id="child_cat_div">
          <label for="child_cat_id">Sub Kategori</label>
          <select name="child_cat_id" id="child_cat_id" class="form-control">
              <option value="">--Pilih sub kategori--</option>
              
          </select>
        </div>

        <div class="form-group">
          <label for="price" class="col-form-label">Harga (IDR) <span class="text-danger">*</span></label>
          <input id="price" type="number" name="price" placeholder="Masukkan harga"  value="{{$product->price}}" class="form-control">
          @error('price')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="discount" class="col-form-label">Diskon (%)</label>
          <input id="discount" type="number" name="discount" min="0" max="100" placeholder="Masukkan diskon"  value="{{$product->discount}}" class="form-control">
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
            @php
              $variants = $product->variant ? explode(',', $product->variant) : [];
              if (old('variant')) {
                  $variants = old('variant');
              }
            @endphp
            @foreach($variants as $variant)
              @if(!empty(trim($variant)))
              <div class="badge badge-primary mr-2 mb-2 p-2 d-inline-flex align-items-center">
                {{ $variant }}
                <button type="button" class="close ml-2 remove-variant" style="font-size: 1rem;">&times;</button>
              </div>
              @endif
            @endforeach
          </div>
          
          <!-- Input hidden untuk menyimpan varian -->
          <div id="variant-inputs">
            @if(old('variant'))
              @foreach(old('variant') as $variant)
                @if(!empty(trim($variant)))
                  <input type="hidden" name="variant[]" value="{{ $variant }}">
                @endif
              @endforeach
            @elseif($product->variant)
              @foreach(explode(',', $product->variant) as $variant)
                @if(!empty(trim($variant)))
                  <input type="hidden" name="variant[]" value="{{ trim($variant) }}">
                @endif
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
          <select name="brand_id" class="form-control">
              <option value="">--Pilih Brand--</option>
             @foreach($brands as $brand)
              <option value="{{$brand->id}}" {{(($product->brand_id==$brand->id)? 'selected':'')}}>{{$brand->title}}</option>
             @endforeach
          </select>
        </div>

        <div class="form-group">
          <label for="condition">Status Produk</label>
          <select name="condition" class="form-control">
              <option value="">--Pilih Status Produk--</option>
              <option value="default" {{(($product->condition=='default')? 'selected':'')}}>Default</option>
              <option value="new" {{(($product->condition=='new')? 'selected':'')}}>Baru</option>
              <option value="hot" {{(($product->condition=='hot')? 'selected':'')}}>Unggulan</option>
          </select>
        </div>

        <div class="form-group">
          <label for="stock">Kuantitas <span class="text-danger">*</span></label>
          <input id="quantity" type="number" name="stock" min="0" placeholder="Enter quantity"  value="{{$product->stock}}" class="form-control">
          @error('stock')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        <div class="form-group">
          <label for="inputPhoto" class="col-form-label">Foto <span class="text-danger">*</span></label>
          <div class="input-group">
              <span class="input-group-btn">
                  <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary text-white">
                  <i class="fas fa-image"></i> Pilih
                  </a>
              </span>
          <input id="thumbnail" class="form-control" type="text" name="photo" value="{{$product->photo}}">
        </div>
        <div id="holder" style="margin-top:15px;max-height:100px;"></div>
          @error('photo')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        
        <div class="form-group">
          <label for="status" class="col-form-label">Status <span class="text-danger">*</span></label>
          <select name="status" class="form-control">
            <option value="active" {{(($product->status=='active')? 'selected' : '')}}>Active</option>
            <option value="inactive" {{(($product->status=='inactive')? 'selected' : '')}}>Inactive</option>
        </select>
          @error('status')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        <div class="form-group mb-3">
           <button class="btn btn-success" type="submit">Update</button>
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
      placeholder: "Write short description.....",
      tabsize: 2,
      height: 150
    });

    $('#description').summernote({
      placeholder: "Write detail description.....",
      tabsize: 2,
      height: 150
    });

    // Menangani penambahan varian baru
    $('#add-variant').click(function() {
      const variantInput = $('#new-variant');
      const variantValue = variantInput.val().trim();
      
      if (variantValue) {
        // Cek apakah varian sudah ada
        let isDuplicate = false;
        $('.badge').each(function() {
          if ($(this).clone().find('.remove-variant').remove().end().text().trim() === variantValue) {
            isDuplicate = true;
            return false; // Keluar dari loop
          }
        });
        
        if (!isDuplicate) {
          // Tambahkan ke daftar varian yang terlihat
          const variantItem = `
            <div class="badge badge-primary mr-2 mb-2 p-2 d-inline-flex align-items-center">
              ${variantValue}
              <button type="button" class="close ml-2 remove-variant" style="font-size: 1rem;">&times;</button>
            </div>`;
          
          $('#variant-list').append(variantItem);
          
          // Tambahkan input hidden untuk form
          $('#variant-inputs').append(`<input type="hidden" name="variant[]" value="${variantValue}">`);
        } else {
          alert('Varian sudah ada dalam daftar');
        }
        
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
  var  child_cat_id='{{$product->child_cat_id}}';
        // alert(child_cat_id);
        $('#cat_id').change(function(){
            var cat_id=$(this).val();

            if(cat_id !=null){
                // ajax call
                $.ajax({
                    url:"/admin/category/"+cat_id+"/child",
                    type:"POST",
                    data:{
                        _token:"{{csrf_token()}}"
                    },
                    success:function(response){
                        if(typeof(response)!='object'){
                            response=$.parseJSON(response);
                        }
                        var html_option="<option value=''>--Select any one--</option>";
                        if(response.status){
                            var data=response.data;
                            if(response.data){
                                $('#child_cat_div').removeClass('d-none');
                                $.each(data,function(id,title){
                                    html_option += "<option value='"+id+"' "+(child_cat_id==id ? 'selected ' : '')+">"+title+"</option>";
                                });
                            }
                            else{
                                console.log('no response data');
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

        });
        if(child_cat_id!=null){
            $('#cat_id').change();
        }
</script>
@endpush