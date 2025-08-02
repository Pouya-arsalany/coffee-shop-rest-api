<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'total_price', 'status'];

    // An order belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // An order can have many order items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function table()
    {
        return $this->belongsTo(\App\Models\Table::class);
    }
}
