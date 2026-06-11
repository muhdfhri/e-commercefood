<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Order;
use App\Models\ProductReview;
use App\Models\PostComment;
use App\Rules\MatchOldPassword;
use Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */


    public function index(){
        return view('user.index');
    }

    public function profile(){
        $profile=Auth()->user();
        // return $profile;
        return view('user.users.profile')->with('profile',$profile);
    }

    public function profileUpdate(Request $request,$id){
        // return $request->all();
        $user=User::findOrFail($id);
        $data=$request->all();
        $status=$user->fill($data)->save();
        if($status){
            request()->session()->flash('success','Profil berhasil diperbarui');
        }
        else{
            request()->session()->flash('error','Gagal memperbarui profil, silahkan coba lagi');
        }
        return redirect()->back();
    }

    // Order
    public function orderIndex(){
        $orders=Order::orderBy('id','DESC')->where('user_id',auth()->user()->id)->paginate(10);
        return view('user.order.index')->with('orders',$orders);
    }
    public function userOrderDelete($id)
    {
        $order=Order::find($id);
        if($order){
           if($order->status=="process" || $order->status=='delivered' || $order->status=='cancel'){
                return redirect()->back()->with('error','Anda tidak dapat menghapus pesanan ini sekarang');
           }
           else{
                $status=$order->delete();
                if($status){
                    request()->session()->flash('success','Pesanan berhasil dihapus');
                }
                else{
                    request()->session()->flash('error','Gagal menghapus pesanan, silahkan coba lagi');
                }
                return redirect()->route('user.order.index');
           }
        }
        else{
            request()->session()->flash('error','Pesanan tidak ditemukan');
            return redirect()->back();
        }
    }

    public function orderShow($id)
    {
        $order=Order::find($id);
        // return $order;
        return view('user.order.show')->with('order',$order);
    }
    
    public function confirmDelivery(Request $request, $id)
    {
        $request->validate([
            'proof_of_delivery' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);
        
        $order = Order::where('id', $id)->where('user_id', auth()->user()->id)->firstOrFail();
        
        if ($order->status == 'delivered') {
            return redirect()->back()->with('error', 'Pesanan sudah dikonfirmasi.');
        }

        if ($request->hasFile('proof_of_delivery')) {
            $file = $request->file('proof_of_delivery');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/proof_of_delivery'), $filename);
            
            \App\Models\OrderConfirmation::create([
                'order_id' => $order->id,
                'user_id' => auth()->user()->id,
                'photo_path' => 'storage/proof_of_delivery/' . $filename,
                'notes' => $request->notes
            ]);
            
            $order->status = 'delivered'; // Finished / Selesai
            if ($order->payment_status == 'unpaid') {
                $order->payment_status = 'paid';
            }
            $order->save();
            
            return redirect()->back()->with('success', 'Berhasil mengkonfirmasi penerimaan barang. Terima kasih!');
        }

        return redirect()->back()->with('error', 'Gagal mengupload bukti penerimaan.');
    }

    public function submitComplaint(Request $request, $id)
    {
        \Log::info('Submit Complaint Hit', ['order_id' => $id, 'data' => $request->all()]);
        $request->validate([
            'complaint_reason' => 'required|string|min:10',
        ]);

        $order = Order::where('id', $id)->where('user_id', auth()->user()->id)->firstOrFail();

        // Cek jika sudah ada komplain untuk pesanan ini
        $existingComplaint = \App\Models\OrderComplaint::where('order_id', $order->id)->first();
        if ($existingComplaint) {
            return redirect()->back()->with('error', 'Anda sudah mengajukan komplain untuk pesanan ini.');
        }

        \App\Models\OrderComplaint::create([
            'order_id' => $order->id,
            'user_id' => auth()->user()->id,
            'reason' => $request->complaint_reason,
            'status' => 'pending'
        ]);

        $order->status = 'complaint';
        $order->save();

        return redirect()->back()->with('success', 'Komplain Anda berhasil dikirim. Kami akan segera meninjau masalah Anda.');
    }

    // Product Review
    public function productReviewIndex(){
        $reviews=ProductReview::getAllUserReview();
        return view('user.review.index')->with('reviews',$reviews);
    }

    public function productReviewEdit($id)
    {
        $review=ProductReview::find($id);
        // return $review;
        return view('user.review.edit')->with('review',$review);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function productReviewUpdate(Request $request, $id)
    {
        $review=ProductReview::find($id);
        if($review){
            $data=$request->all();
            $status=$review->fill($data)->update();
            if($status){
                request()->session()->flash('success','Ulasan berhasil diperbarui');
            }
            else{
                request()->session()->flash('error','Gagal memperbarui ulasan, silahkan coba lagi');
            }
        }
        else{
            request()->session()->flash('error','Ulasan tidak ditemukan');
        }

        return redirect()->route('user.productreview.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function productReviewDelete($id)
    {
        $review=ProductReview::find($id);
        $status=$review->delete();
        if($status){
            request()->session()->flash('success','Ulasan berhasil dihapus');
        }
        else{
            request()->session()->flash('error','Gagal menghapus ulasan, silahkan coba lagi');
        }
        return redirect()->route('user.productreview.index');
    }

    public function userComment()
    {
        $comments=PostComment::getAllUserComments();
        return view('user.comment.index')->with('comments',$comments);
    }
    public function userCommentDelete($id){
        $comment=PostComment::find($id);
        if($comment){
            $status=$comment->delete();
            if($status){
                request()->session()->flash('success','Komentar berhasil dihapus');
            }
            else{
                request()->session()->flash('error','Gagal menghapus komentar, silahkan coba lagi');
            }
            return back();
        }
        else{
            request()->session()->flash('error','Komentar tidak ditemukan');
            return redirect()->back();
        }
    }
    public function userCommentEdit($id)
    {
        $comments=PostComment::find($id);
        if($comments){
            return view('user.comment.edit')->with('comment',$comments);
        }
        else{
            request()->session()->flash('error','Komentar tidak ditemukan');
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function userCommentUpdate(Request $request, $id)
    {
        $comment=PostComment::find($id);
        if($comment){
            $data=$request->all();
            // return $data;
            $status=$comment->fill($data)->update();
            if($status){
                request()->session()->flash('success','Komentar berhasil diperbarui');
            }
            else{
                request()->session()->flash('error','Gagal memperbarui komentar, silahkan coba lagi');
            }
            return redirect()->route('user.post-comment.index');
        }
        else{
            request()->session()->flash('error','Komentar tidak ditemukan');
            return redirect()->back();
        }

    }

    public function changePassword(){
        return view('user.layouts.userPasswordChange');
    }
    public function changPasswordStore(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);
   
        User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);
   
        return redirect()->route('user')->with('success','Password berhasil diubah');
    }

    
}
