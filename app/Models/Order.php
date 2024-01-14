<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [] ;

    protected $with = ['medicines'];

    public function medicines()
    {
        return $this->belongsToMany(Medicine::class, 'medicine_orders')->withPivot('medicine_amount')->withTimestamps();
    }


    public function pharmacist():BelongsTo
    {
        return $this->belongsTo(User::class) ;
    }
    public function warehouse():BelongsTo
    {
        return $this->belongsTo(Warehouse::class) ;
    }


}
