<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Wishlist;
use App\Models\Cart;
use Illuminate\Support\Str;
use Helper;
class CartController extends Controller
{
    protected $product=null;
    public function __construct(Product $product){
        $this->product=$product;
    }

    public function addToCart(Request $request){
        // dd($request->all());
        if (empty($request->slug)) {
            request()->session()->flash('error','Produk tidak valid');
            return back();
        }        
        $product = Product::where('slug', $request->slug)->first();
        // return $product;
        if (empty($product)) {
            request()->session()->flash('error','Produk tidak ditemukan');
            return back();
        }

        $already_cart = Cart::where('user_id', auth()->user()->id)->where('order_id',null)->where('product_id', $product->id)->first();
        // return $already_cart;
        if($already_cart) {
            // dd($already_cart);
            $already_cart->quantity = $already_cart->quantity + 1;
            $already_cart->amount = $product->price+ $already_cart->amount;
            // return $already_cart->quantity;
            if ($already_cart->product->stock < $already_cart->quantity || $already_cart->product->stock <= 0) return back()->with('error','Stok tidak mencukupi!.');
            $already_cart->save();
            
        }else{
            
            $cart = new Cart;
            $cart->user_id = auth()->user()->id;
            $cart->product_id = $product->id;
            $cart->price = ($product->price-($product->price*$product->discount)/100);
            $cart->quantity = 1;
            $cart->amount=$cart->price*$cart->quantity;
            if ($cart->product->stock < $cart->quantity || $cart->product->stock <= 0) return back()->with('error','Stok tidak mencukupi!.');
            $cart->save();
            $wishlist=Wishlist::where('user_id',auth()->user()->id)->where('cart_id',null)->update(['cart_id'=>$cart->id]);
        }
        request()->session()->flash('success','Produk berhasil ditambahkan ke keranjang');
        return back();       
    }  

    public function singleAddToCart(Request $request){
        // Debug: Tampilkan semua data request
        \Log::info('Add to cart request', ['data' => $request->all()]);
        
        $request->validate([
            'slug'      => 'required',
            'quant'     => 'required|array',
            'variant'   => 'nullable|string',
        ]);

        $product = Product::where('slug', $request->slug)->first();
        if(empty($product)) {
            request()->session()->flash('error', 'Produk tidak ditemukan');
            return back();
        }
        
        // Ambil kuantitas dari input
        $quantity = (int) ($request->quant[1] ?? 1);
        
        if($product->stock < $quantity){
            return back()->with('error', 'Stok tidak mencukupi, silakan pilih jumlah yang lebih sedikit.');
        }
        
        if($quantity < 1) {
            request()->session()->flash('error', 'Jumlah produk tidak valid');
            return back();
        }    

        // Ambil varian yang dipilih dari input hidden
        $selectedVariant = $request->input('variant');
        
        // Debug: Tampilkan varian yang dipilih
        \Log::info('Selected Variant', ['variant' => $selectedVariant]);
        \Log::info('Product Info', ['product_id' => $product->id, 'product_name' => $product->title]);
        \Log::info('User Info', ['user_id' => auth()->id()]);
        
        // Cek apakah produk memiliki varian
        $hasVariants = !empty($product->variant);
        
        // Jika produk memiliki varian tapi tidak ada varian yang dipilih
        if ($hasVariants && empty($selectedVariant)) {
            return back()->with('error', 'Silakan pilih varian terlebih dahulu');
        }
        
        // Cari keranjang yang sudah ada dengan produk dan varian yang sama
        $query = Cart::where('user_id', auth()->id())
                    ->where('order_id', null)
                    ->where('product_id', $product->id);
        
        // Jika ada varian yang dipilih, cari berdasarkan varian
        if (!empty($selectedVariant)) {
            $query->where('variant', $selectedVariant);
        } else {
            // Jika tidak ada varian yang dipilih, cari item tanpa varian
            $query->whereNull('variant');
        }
        
        $already_cart = $query->first();
        if ($already_cart) {
            \Log::info('Item keranjang yang sama ditemukan', ['cart' => $already_cart->toArray()]);
        } else {
            \Log::info('Tidak ada item keranjang yang sama ditemukan untuk produk dan varian ini');
        }

        if($already_cart) {
            // Update kuantitas jika produk dengan varian yang sama sudah ada di keranjang
            $newQuantity = $already_cart->quantity + $quantity;
            
            if ($product->stock < $newQuantity) {
                return back()->with('error', 'Stok tidak mencukupi!');
            }
            
            $already_cart->quantity = $newQuantity;
            $already_cart->amount = ($product->price - ($product->price * $product->discount) / 100) * $newQuantity;
            $already_cart->save();
            
            request()->session()->flash('success', 'Produk berhasil ditambahkan ke keranjang');
        } else {
            // Buat item keranjang baru
            $price = $product->price - ($product->price * $product->discount / 100);
            
            $cart = new Cart();
            $cart->user_id = auth()->id();
            $cart->product_id = $product->id;
            $cart->price = $price;
            $cart->quantity = $quantity;
            $cart->amount = $price * $quantity;
            
            // Set variant only if it exists and is not empty
            if (!empty($selectedVariant)) {
                $cart->variant = $selectedVariant;
            }
            
            \Log::info('Creating new cart item:', [
                'product_id' => $cart->product_id,
                'variant' => $cart->variant,
                'quantity' => $cart->quantity,
                'amount' => $cart->amount
            ]);
            
            if ($product->stock < $cart->quantity) {
                return back()->with('error', 'Stok tidak mencukupi!');
            }
            
            $cart->save();
            
            // Log the saved cart item
            \Log::info('Cart item saved:', [
                'id' => $cart->id,
                'variant' => $cart->variant,
                'saved_at' => now()
            ]);
            
            // Update wishlist jika ada
            Wishlist::where('user_id', auth()->id())
                   ->where('cart_id', null)
                   ->where('product_id', $product->id)
                   ->update(['cart_id' => $cart->id]);
            
            request()->session()->flash('success', 'Produk berhasil ditambahkan ke keranjang');
        }
        
        return back();
    }
    
    public function cartDelete(Request $request){
        $cart = Cart::find($request->id);
        if ($cart) {
            $cart->delete();
            request()->session()->flash('success','Item keranjang berhasil dihapus');
            return back();  
        }
        request()->session()->flash('error','Gagal menghapus item keranjang');
        return back();       
    }     

    public function cartUpdate(Request $request){
        // dd($request->all());
        if($request->quant){
            $error = array();
            $success = '';
            // return $request->quant;
            foreach ($request->quant as $k=>$quant) {
                // return $k;
                $id = $request->qty_id[$k];
                // return $id;
                $cart = Cart::find($id);
                // return $cart;
                if($quant > 0 && $cart) {
                    // return $quant;

                    if($cart->product->stock < $quant){
                        request()->session()->flash('error','Stok tidak mencukupi!');
                        return back();
                    }
                    $cart->quantity = ($cart->product->stock > $quant) ? $quant  : $cart->product->stock;
                    // return $cart;
                    
                    if ($cart->product->stock <=0) continue;
                    $after_price=($cart->product->price-($cart->product->price*$cart->product->discount)/100);
                    $cart->amount = $after_price * $quant;
                    // return $cart->price;
                    $cart->save();
                    $success = 'Item keranjang berhasil diperbarui!';
                }else{
                    $error[] = 'Item keranjang tidak valid!';
                }
            }
            return back()->with($error)->with('success', $success);
        }else{
            return back()->with('Item keranjang tidak valid!');
        }    
    }

    // public function addToCart(Request $request){
    //     // return $request->all();
    //     if(Auth::check()){
    //         $qty=$request->quantity;
    //         $this->product=$this->product->find($request->pro_id);
    //         if($this->product->stock < $qty){
    //             return response(['status'=>false,'msg'=>'Out of stock','data'=>null]);
    //         }
    //         if(!$this->product){
    //             return response(['status'=>false,'msg'=>'Product not found','data'=>null]);
    //         }
    //         // $session_id=session('cart')['session_id'];
    //         // if(empty($session_id)){
    //         //     $session_id=Str::random(30);
    //         //     // dd($session_id);
    //         //     session()->put('session_id',$session_id);
    //         // }
    //         $current_item=array(
    //             'user_id'=>auth()->user()->id,
    //             'id'=>$this->product->id,
    //             // 'session_id'=>$session_id,
    //             'title'=>$this->product->title,
    //             'summary'=>$this->product->summary,
    //             'link'=>route('product-detail',$this->product->slug),
    //             'price'=>$this->product->price,
    //             'photo'=>$this->product->photo,
    //         );
            
    //         $price=$this->product->price;
    //         if($this->product->discount){
    //             $price=($price-($price*$this->product->discount)/100);
    //         }
    //         $current_item['price']=$price;

    //         $cart=session('cart') ? session('cart') : null;

    //         if($cart){
    //             // if anyone alreay order products
    //             $index=null;
    //             foreach($cart as $key=>$value){
    //                 if($value['id']==$this->product->id){
    //                     $index=$key;
    //                 break;
    //                 }
    //             }
    //             if($index!==null){
    //                 $cart[$index]['quantity']=$qty;
    //                 $cart[$index]['amount']=ceil($qty*$price);
    //                 if($cart[$index]['quantity']<=0){
    //                     unset($cart[$index]);
    //                 }
    //             }
    //             else{
    //                 $current_item['quantity']=$qty;
    //                 $current_item['amount']=ceil($qty*$price);
    //                 $cart[]=$current_item;
    //             }
    //         }
    //         else{
    //             $current_item['quantity']=$qty;
    //             $current_item['amount']=ceil($qty*$price);
    //             $cart[]=$current_item;
    //         }

    //         session()->put('cart',$cart);
    //         return response(['status'=>true,'msg'=>'Cart successfully updated','data'=>$cart]);
    //     }
    //     else{
    //         return response(['status'=>false,'msg'=>'You need to login first','data'=>null]);
    //     }
    // }

    // public function removeCart(Request $request){
    //     $index=$request->index;
    //     // return $index;
    //     $cart=session('cart');
    //     unset($cart[$index]);
    //     session()->put('cart',$cart);
    //     return redirect()->back()->with('success','Successfully remove item');
    // }

    public function checkout(Request $request){
        // Get active payment methods
        $paymentMethods = \App\Models\PaymentMethod::where('status', 'active')->get();
        
        return view('frontend.pages.checkout', compact('paymentMethods'));
    }
}
