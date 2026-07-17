<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Auth\Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class User extends Model implements AuthenticatableContract {
    use Authenticatable, HasFactory, HasApiTokens;

    protected $fillable = ['name', 'email', 'phone', 'password'];
    protected $hidden = ['password'];

    public function leads(): HasMany { return $this->hasMany(Lead::class); }
}
