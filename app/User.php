<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function favoritesBond()
    {
        return $this->belongsToMany(Bond::class, 'favorite_bonds', 'user_id', 'bond_id')->withTimeStamps();
    }

    public function trashBond()
    {
        return $this->belongsToMany(Bond::class, 'trash_bonds', 'user_id', 'bond_id')->withTimeStamps();
    }

    public function favoritesStock()
    {
        return $this->belongsToMany(Stock::class, 'stock_favorites', 'user_id', 'stock_id')->withTimeStamps();
    }
}
