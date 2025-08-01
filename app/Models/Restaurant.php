<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MenuItem;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable=[
        'user_id',
        'name',
        'image',
        'address',
        'menu',
        'status'
    ];

    public function scopeActive($query){
        return $query->where('status', 'active');
    }

    public function scopeRecommendedForMeal($query, $mealName){
        return $query->active()->whereRaw('LOWER(menu) LIKE ?', ['%' . strtolower($mealName). '%']);
    }

    public function getImageUrlAttribute()
    {
        $image = $this->attributes['image'] ?? null;
        return $image
            ? asset('storage/' . $image)
            : asset('images/default-restaurant.jpg');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function menuItems(){
        return $this->hasMany(MenuItem::class);
    }
    
}
