<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\PaymentMethod;

class Order extends Model
{
    protected $fillable=[
        'user_id',
        'order_number',
        'sub_total',
        'quantity',
        'delivery_charge',
        'status',
        'total_amount',
        'first_name',
        'last_name',
        'country',
        'post_code',
        'address1',
        'address2',
        'phone',
        'email',
        'payment_method_id',
        'payment_status',
        'payment_proof',
        'payment_verified_at',
        'verified_by',
        'payment_notes',
        'shipping_id',
        'coupon'
    ];

    public function cart_info(){
        return $this->hasMany('App\Models\Cart','order_id','id')->with('product');
    }
    
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
    
    public static function getAllOrder($id){
        return Order::with(['cart_info' => function($query) {
            $query->with('product');
        }])->find($id);
    }
    
    public static function countActiveOrder(){
        $data=Order::count();
        if($data){
            return $data;
        }
        return 0;
    }
    
    public function cart(){
        return $this->hasMany(Cart::class)->with('product');
    }

    public function shipping(){
        return $this->belongsTo(Shipping::class,'shipping_id');
    }
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

}
