<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Restaurant;
use App\Models\Order;

class MenuItem extends Model
{
public function orders(){
    return $this->belongsToMany(Order::class, 'order_items', 'menu_item_id', 'order_id')
                ->withPivot('quantity', 'price')
                ->withTimestamps();
}
}
