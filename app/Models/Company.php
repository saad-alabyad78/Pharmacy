<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;
    protected $guarded = [] ;

    //protected $with = ['medicines'] ;

    public function medicines():HasMany
    {
        return $this->hasMany(Medicine::class) ;
    }
}
