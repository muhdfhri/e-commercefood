<?php

namespace App\Http\Controllers;
use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Models\Cart;
class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $coupon=Coupon::orderBy('id','DESC')->paginate('10');
        return view('backend.coupon.index')->with('coupons',$coupon);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.coupon.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();
        $this->validate($request,[
            'code'=>'string|required',
            'type'=>'required|in:fixed,percent',
            'value'=>'required|numeric',
            'status'=>'required|in:active,inactive'
        ]);
        $data=$request->all();
        $status=Coupon::create($data);
        if($status){
            request()->session()->flash('success','Kupon berhasil ditambahkan');
        }
        else{
            request()->session()->flash('error','Gagal menambahkan kupon, silahkan coba lagi');
        }
        return redirect()->route('coupon.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $coupon=Coupon::find($id);
        if($coupon){
            return view('backend.coupon.edit')->with('coupon',$coupon);
        }
        else{
            return view('backend.coupon.index')->with('error','Kupon tidak ditemukan');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $coupon=Coupon::find($id);
        $this->validate($request,[
            'code'=>'string|required',
            'type'=>'required|in:fixed,percent',
            'value'=>'required|numeric',
            'status'=>'required|in:active,inactive'
        ]);
        $data=$request->all();
        
        $status=$coupon->fill($data)->save();
        if($status){
            request()->session()->flash('success','Kupon berhasil diperbarui');
        }
        else{
            request()->session()->flash('error','Gagal memperbarui kupon, silahkan coba lagi');
        }
        return redirect()->route('coupon.index');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $coupon=Coupon::find($id);
        if($coupon){
            $status=$coupon->delete();
            if($status){
                request()->session()->flash('success','Kupon berhasil dihapus');
            }
            else{
                request()->session()->flash('error','Gagal menghapus kupon, silahkan coba lagi');
            }
            return redirect()->route('coupon.index');
        }
        else{
            request()->session()->flash('error','Kupon tidak ditemukan');
            return redirect()->back();
        }
    }

    public function couponStore(Request $request){
        // Periksa apakah ada item di keranjang
        $cartItems = Cart::where('user_id', auth()->user()->id)
                        ->where('order_id', null)
                        ->count();
        
        if ($cartItems == 0) {
            return back()->with('error', 'Anda belum menambahkan item ke keranjang. Silakan tambahkan minimal 1 item terlebih dahulu.');
        }
        
        // Cek kupon
        $coupon = Coupon::where('code', $request->code)->first();
        
        if (!$coupon) {
            return back()->with('error', 'Kode kupon tidak valid, silakan coba lagi');
        }
        
        // Hitung total harga
        $total_price = Cart::where('user_id', auth()->user()->id)
                          ->where('order_id', null)
                          ->sum('price');
        
        // Simpan kupon ke session
        session()->put('coupon', [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'value' => $coupon->discount($total_price)
        ]);
        
        return back()->with('success', 'Kupon berhasil diterapkan');
    }
}
