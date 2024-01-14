<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = [] ;
    protected $with = ['orders'] ;
    public function orders():HasMany
    {
        return $this->hasMany(Order::class);
    }
    public function favoriteMedicines()
    {
        return $this->belongsToMany(Medicine::class, 'favorites');
    }


    public function warehouse():BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];
}
