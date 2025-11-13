<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Shipping;
use App\User;
use PDF;
use Notification;
use Helper;
use Illuminate\Support\Str;
use App\Notifications\StatusNotification;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::with('paymentMethod')->orderBy('id','DESC')->paginate(10);
        return view('backend.order.index')->with('orders', $orders);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'first_name'=>'string|required',
            'last_name'=>'string|required',
            'address1'=>'string|required',
            'address2'=>'string|nullable',
            'coupon'=>'nullable|numeric',
            'phone'=>'numeric|required',
            'post_code'=>'string|nullable',
            'email'=>'string|required',
            'payment_method'=>'required|string', // Nama input dari form
        ]);
    
        if(empty(Cart::where('user_id',auth()->user()->id)->where('order_id',null)->first())){
            request()->session()->flash('error','Keranjang Anda kosong!');
            return back();
        }
    
        $order=new Order();
        $order_data=$request->all();
        $order_data['order_number']='ORD-'.strtoupper(Str::random(10));
        $order_data['user_id']=$request->user()->id;
        $order_data['shipping_id']=$request->shipping;
        $shipping=Shipping::where('id',$order_data['shipping_id'])->pluck('price');
        
        $order_data['sub_total']=Helper::totalCartPrice();
        $order_data['quantity']=Helper::cartCount();
        
        if(session('coupon')){
            $order_data['coupon']=session('coupon')['value'];
        }
        
        if($request->shipping){
            if(session('coupon')){
                $order_data['total_amount']=Helper::totalCartPrice()+$shipping[0]-session('coupon')['value'];
            }
            else{
                $order_data['total_amount']=Helper::totalCartPrice()+$shipping[0];
            }
        }
        else{
            if(session('coupon')){
                $order_data['total_amount']=Helper::totalCartPrice()-session('coupon')['value'];
            }
            else{
                $order_data['total_amount']=Helper::totalCartPrice();
            }
        }
        
        $order_data['status']="new";
        
        // Cari payment_method_id berdasarkan code yang dipilih user
        $paymentMethod = \App\Models\PaymentMethod::where('code', $request->payment_method)->first();
        if($paymentMethod) {
            $order_data['payment_method_id'] = $paymentMethod->id;
        }
        
        // Hapus payment_method dari array karena tidak ada kolom ini di database
        unset($order_data['payment_method']);
        
        // Handle payment proof upload
        $paymentMethod = \App\Models\PaymentMethod::where('code', $request->payment_method)->first();
        if($paymentMethod) {
            $order_data['payment_method_id'] = $paymentMethod->id;
        }

        // Hapus payment_method dari array karena tidak ada kolom ini di database
        unset($order_data['payment_method']);

        // Handle payment proof upload
        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $fileName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
            
            // Create directory if it doesn't exist
            $path = public_path('storage/payment_proofs');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            
            // Move the file to the storage directory
            $file->move($path, $fileName);
            
            // Save the file path to the database
            $order_data['payment_proof'] = 'storage/payment_proofs/'.$fileName;
            $order_data['payment_status'] = 'unpaid'; // Set status unpaid untuk verifikasi
        } else {
            $paymentCode = strtolower($request->payment_method);
            if ($paymentCode === 'cod' || $paymentCode === 'bayar_di_tempat') {
                $order_data['payment_status'] = 'unpaid';
            } else {
                $order_data['payment_status'] = 'unpaid';
            }
        }
        
        $order->fill($order_data);
        $status=$order->save();
        
        if($order) {
            $users=User::where('role','admin')->first();
            $details=[
                'title'=>'Pesanan Baru',
                'actionURL'=>route('order.show',$order->id),
                'fas'=>'fa-file-alt'
            ];
            Notification::send($users, new StatusNotification($details));
            
            if(request('payment_method')=='paypal'){
                return redirect()->route('payment')->with(['id'=>$order->id]);
            }
            else{
                session()->forget('cart');
                session()->forget('coupon');
            }
            
            Cart::where('user_id', auth()->user()->id)->where('order_id', null)->update(['order_id' => $order->id]);
            
            request()->session()->flash('success','Produk Anda berhasil dipesan');
            return redirect()->route('home');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::with(['cart' => function($query) {
            $query->with('product');
        },'paymentMethod'])->find($id);
        
        if (!$order) {
            return redirect()->route('order.index')->with('error', 'Pesanan tidak ditemukan');
        }
        
        return view('backend.order.show')->with('order', $order);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = Order::with('paymentMethod')->find($id);
        if (!$order) {
            request()->session()->flash('error', 'Pesanan tidak ditemukan');
            return redirect()->route('order.index');
        }
        
        $paymentMethods = \App\Models\PaymentMethod::where('status', 'active')->get();
        
        return view('backend.order.edit', [
            'order' => $order,
            'paymentMethods' => $paymentMethods
        ]);
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
        $order = Order::find($id);
        if(!$order) {
            request()->session()->flash('error', 'Pesanan tidak ditemukan');
            return redirect()->route('order.index');
        }
        
        $this->validate($request, [
            'status' => 'required|in:new,process,delivered,cancel',
            'payment_status' => 'required|in:unpaid,paid',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();
        
        // Handle payment proof upload
        if ($request->hasFile('payment_proof')) {
            // Delete old payment proof if exists
            if ($order->payment_proof && file_exists(public_path($order->payment_proof))) {
                unlink(public_path($order->payment_proof));
            }
            
            $image = $request->file('payment_proof');
            $filename = time() . '_' . $order->order_number . '.' . $image->getClientOriginalExtension();
            $path = 'uploads/payment_proofs';
            
            // Create directory if not exists
            if (!file_exists(public_path($path))) {
                mkdir(public_path($path), 0755, true);
            }
            
            $image->move(public_path($path), $filename);
            $data['payment_proof'] = $path . '/' . $filename;
        }

        // Update order
        $order->fill($data);
        
        // If status is delivered, update product stock and send notification
        if ($request->status == 'delivered' && $order->status != 'delivered') {
            foreach ($order->cart as $cart) {
                $product = $cart->product;
                $product->stock -= $cart->quantity;
                $product->save();
            }
            
            // Send notification to user
            $status = Order::where('id', $id)->select('status')->first();
            if (class_exists('App\Notifications\StatusUpdateNotification')) {
                $status->notify(new \App\Notifications\StatusUpdateNotification($status));
            }
        }
        
        $order->save();

        request()->session()->flash('success', 'Pesanan berhasil diperbarui');
        return redirect()->route('order.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order=Order::find($id);
        if($order){
            $status=$order->delete();
            if($status){
                request()->session()->flash('success','Pesanan berhasil dihapus');
            }
            else{
                request()->session()->flash('error','Gagal menghapus pesanan, silahkan coba lagi');
            }
            return redirect()->route('order.index');
        }
        else{
            request()->session()->flash('error','Pesanan tidak ditemukan');
            return redirect()->back();
        }
    }

    public function orderTrack(){
        return view('frontend.pages.order-track');
    }

    public function productTrackOrder(Request $request){
        $request->validate([
            'order_number' => 'required|string|max:255'
        ]);
        
        $query = Order::with('paymentMethod')->where('order_number', $request->order_number);
        
        // Jika user login, filter berdasarkan user_id
        if (auth()->check()) {
            $query->where('user_id', auth()->id());
        }
        
        $order = $query->first();
        
        // Debug: Cek data order
        // dd($order->toArray());
        
        if($order){
            return view('frontend.pages.order-track', compact('order'));
        }
        else{
            request()->session()->flash('error','Nomor pesanan tidak valid, silahkan coba lagi');
            return back();
        }
    }

    /**
     * Generate and download order invoice as PDF
     *
     * @param int $id Order ID
     * @return \Illuminate\Http\Response
     */
    public function pdf($id)
    {
        try {
            // Load order with necessary relationships
            $order = Order::with([
                'cart_info.product',
                'user',
                'paymentMethod'
            ])->findOrFail($id);
            
            // Load settings
            $settings = DB::table('settings')->first();
            
            // Generate file name
            $file_name = 'invoice-'.$order->order_number.'.pdf';
            
            // Generate PDF
            $pdf = \PDF::loadView('backend.order.pdf', [
                'order' => $order,
                'settings' => $settings
            ])
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => false,
                    'dpi' => 96,
                    'defaultFont' => 'dejavu sans',
                    'isPhpEnabled' => true,
                    'isFontSubsettingEnabled' => true,
                    'isJavascriptEnabled' => false
                ]);
            
            // Force download with proper headers
            return $pdf->download($file_name, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="'.$file_name.'"',
                'Cache-Control' => 'private, max-age=0, must-revalidate',
                'Pragma' => 'public',
                'Expires' => 'Sat, 26 Jul 1997 05:00:00 GMT',
                'Last-Modified' => gmdate('D, d M Y H:i:s').' GMT'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('PDF Generation Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal membuat PDF: ' . $e->getMessage());
        }
    }
    // Income chart
    public function incomeChart(Request $request){
        $year=\Carbon\Carbon::now()->year;
        // dd($year);
        $items=Order::with(['cart_info'])->whereYear('created_at',$year)->where('status','delivered')->get()
            ->groupBy(function($d){
                return \Carbon\Carbon::parse($d->created_at)->format('m');
            });
            // dd($items);
        $result=[];
        foreach($items as $month=>$item_collections){
            foreach($item_collections as $item){
                $amount=$item->cart_info->sum('amount');
                // dd($amount);
                $m=intval($month);
                // return $m;
                isset($result[$m]) ? $result[$m] += $amount :$result[$m]=$amount;
            }
        }
        $data=[];
        for($i=1; $i <=12; $i++){
            $monthName=date('F', mktime(0,0,0,$i,1));
            $data[$monthName] = (!empty($result[$i]))? number_format((float)($result[$i]), 2, '.', '') : 0.0;
        }
        return $data;
    }
    
    /**
     * Download payment proof file
     *
     * @param int $id Order ID
     * @return \Illuminate\Http\Response
     */
    public function downloadPaymentProof($id)
    {
        $order = Order::findOrFail($id);
        
        if (!$order->payment_proof) {
            return redirect()->back()->with('error', 'Bukti pembayaran tidak ditemukan.');
        }
        
        $filePath = public_path($order->payment_proof);
        
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File bukti pembayaran tidak ditemukan.');
        }
        
        $fileName = 'bukti-pembayaran-' . $order->order_number . '.' . pathinfo($filePath, PATHINFO_EXTENSION);
        
        return response()->download($filePath, $fileName);
    }
}
