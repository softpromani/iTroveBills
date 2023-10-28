<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;
    public static $role_admin = 'Admin';
    public static $role_customer = 'Customer';
    public static $role_seller = 'Seller';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile',
        'app_logo',
        'app_name',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function activeSubscription()
    {
        return $this->hasMany(SellerSubscription::class, 'seller_id', 'id')->whereNotNull('end_date')->whereDate('end_date', '>=', Carbon::now());
    }
    public function subscriptions()
    {
        return $this->hasMany(SellerSubscription::class, 'seller_id', 'id');
    }

    public function companies()
    {
        return $this->hasMany(Company::class, 'seller_id', 'id');
    }

    public function invoices()
    {
        return $this->hasManyThrough(Invoice::class, Company::class, 'seller_id', 'company_id', 'id', 'id');
    }

}
