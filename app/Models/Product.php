<?php

namespace App\Models;

use App\Services\CurrencyService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'price_usd', 'price_eur'];

    public function getPriceAttribute()
    {
        return (new CurrencyService())->convert($this->price_usd, 'usd', 'eur');
    }
}
