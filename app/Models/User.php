<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Auth\Authenticatable;

class User extends Model {
    use Authenticatable;
    protected $fillable = ['name', 'email', 'phone', 'password'];
    protected $hidden = ['password'];

    public function leads(): HasMany { return $this->hasMany(Lead::class); }
}
