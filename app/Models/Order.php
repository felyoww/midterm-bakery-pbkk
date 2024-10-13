<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'grand_total',
        'payment_method',
        'payment_status',
        'status',
        'currency',
        'shipping_amount',
        'shipping_method',
        'notes'
    ];

    public function getPaymentMethodAttribute($value)
    {
        $map = [
            'ewallet' => 'E-Wallet',
            'cod' => 'Cash On Delivery',
            'paylater' => 'Pay Later',
        ];
        return $map[$value] ?? $value;
    }

    public function getCurrencyAttribute($value)
    {
        $map = [
            'idr' => 'IDR',
        ];
        return $map[$value] ?? $value;
    }

    public function getPaymentStatusAttribute($value)
    {
        $map = [
            'pending' => 'Pending',
            'paid' => 'Paid',
            'failed' => 'Failed'
        ];
        return $map[$value] ?? $value;
    }

    public function getShippingMethodAttribute($value)
    {
        $map = [
            'gojek' => 'Gojek Instant',
            'grab' => 'Grab Instant',
            'lalamove' => 'Lalamove',
        ];
        return $map[$value] ?? $value;
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }
}
