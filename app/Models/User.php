<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Http\Resources\UserResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use /**/HasApiTokens/**/, HasFactory, Notifiable, SoftDeletes;

    const VERIFIED_USER = '1';
    const UNVERIFIED_USER = '0';

    const ADMIN_USER = 'true';
    const REGULAR_USER = 'false';

    public $modelResource = UserResource::class;

    protected $table ='users'; // to tell laravel to use 'users' table for the Seller and the Buyer models.

    protected $dates = ['deleted_at']; // for softDeleting.

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */    protected $fillable = [
        'name',
        'email',
        'password',
        'verified',
        'verification_token',
        'admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
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

    // the Mutators will change the actual data in the database (before storing)
    public function setNameAttribute($name) // the mutator name is Name.
    {
        $this->attributes['name'] = strtolower($name); // this Mutators will change the names to lowercase.
    }

    // the Accessor will not change the actual data, it just for viewing.
    public function getNameAttribute($name)
    {
        return ucwords($name); // this Accessor will show the ferst letter of the names as uppercase.
    }

    public function setEmailAttribute($email)
    {
        $this->attributes['email'] = strtolower($email); // this Mutators will change the emails to lowercase.
    }

    public function isVerified()
    {
        return $this->verified == User::VERIFIED_USER;
    }

    public function isAdmin()
    {
        return $this->admin == User::ADMIN_USER;
    }

    public static function generateVerificationCode()
    {
        return str::random(40);
    }
}
