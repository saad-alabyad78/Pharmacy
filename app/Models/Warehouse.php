<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    use HasFactory;

    protected $guarded = [] ;
    //protected $with = ['medicines'] ;

    public function admins():HasMany
    {
        return $this->HasMany(User::class , 'warehouse_id');
    }
    public function medicines():BelongsToMany
    {
        return $this->belongsToMany(Medicine::class)
        ->withPivot(['id','final_date' , 'amount'])
        ->withTimestamps()
        ->orderByPivot('final_date');
    }
    public function orders():HasMany
    {
        return $this->hasMany(Order::class)
        ->orderBy('date');
    }

}
