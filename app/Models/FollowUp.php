<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Represents a follow-up action taken for a lead.
 *
 * @property int $id
 * @property int $lead_id
 * @property string $method
 * @property string $message
 * @property string|null $sent_at
 *
 * @property-read \App\Models\Lead $lead
 */
class FollowUp extends Model {
    protected $fillable = ['lead_id','method','message','sent_at'];
    public $timestamps = false;

    /**
     * Get the lead that this follow-up belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lead(): BelongsTo { return $this->belongsTo(Lead::class); }
}
