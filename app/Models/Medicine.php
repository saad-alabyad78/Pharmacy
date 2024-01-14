<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Medicine extends Model
{
    use HasFactory;

    protected $guarded = [] ;

    protected $with = ['company' , 'category' ] ;

    public function company():BelongsTo
    {
        return $this->belongsTo(Company::class) ;
    }

    public function category():BelongsTo
    {
        return $this->belongsTo(Category::class) ;
    }

    public function warehouses():BelongsToMany
    {
        return $this->belongsToMany(Warehouse::class)
        ->withPivot(['id','final_date' , 'amount'])
        ->withTimestamps();
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'medicine_orders')->withPivot('medicine_amount')->withTimestamps();
    }




    public function favoritedByUsers()
{
    return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
}

    // public function pharmacist():BelongsToMany
    // {
    //     return $this->belongsToMany(Pharmacist::class) ;
    // }

}
