<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'name',
        
        'phone',
        'street_address',
        'city',
        'province',
        'zip_code'
    ];

    
    public function order(){
        return $this->belongsTo(Order::class);
    }

    
    public function getFullNameAttribute(){
        return "{$this->first_name} {$this->last_name}";
    }

}
