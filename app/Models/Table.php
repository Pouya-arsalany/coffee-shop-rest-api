<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Table extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'seats', 'is_available'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
