<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Represents a sales lead in the system.
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $company
 * @property int|null $value
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $last_contact
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FollowUp[] $followUps
 */
class Lead extends Model {
    protected $fillable = ['user_id','name','phone','email','company','value','status','last_contact','notes'];
    protected $casts = ['last_contact' => 'datetime'];

    /**
     * Get the user that owns the lead.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo { return $this->belongsTo(User::class); }

    /**
     * Get the follow-ups for the lead.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function followUps(): HasMany { return $this->hasMany(FollowUp::class); }
}
