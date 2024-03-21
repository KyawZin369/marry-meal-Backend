<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;
    protected $fillable = [

        'first_name',
        'last_name',
        'shop_name',
        'shop_address',
        'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
