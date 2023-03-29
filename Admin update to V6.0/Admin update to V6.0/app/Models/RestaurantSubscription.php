<?php

namespace App\Models;

use App\Scopes\ZoneScope;
use App\Scopes\RestaurantScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RestaurantSubscription extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    // protected $dates = ['expiry_date'];



    protected $casts = [
        'pos'=>'integer',
        'mobile_app'=>'integer',
        'chat'=>'integer',
        'review'=>'integer',
        'package_id'=>'integer',
        'status'=>'integer',
        'total_package_renewed'=>'integer',
        'self_delivery'=>'integer',
        'restaurant_id'=>'integer',
        'expiry_date'=> 'datetime',
    ];
    public function package()
    {
        return $this->belongsTo(SubscriptionPackage::class,'package_id');
    }
    public function transcations()
    {
        return $this->hasMany(SubscriptionTransaction::class,'restaurant_id');
    }
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    protected static function booted()
    {
        static::addGlobalScope(new ZoneScope);
    }

}
