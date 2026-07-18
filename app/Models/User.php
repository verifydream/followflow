<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Auth\Authenticatable;

/**
 * Represents a user of the application.
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string $password
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Lead[] $leads
 */
class User extends Model {
    use Authenticatable;
    protected $fillable = ['name', 'email', 'phone', 'password'];
    protected $hidden = ['password'];

    /**
     * Get the leads associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function leads(): HasMany { return $this->hasMany(Lead::class); }
}
