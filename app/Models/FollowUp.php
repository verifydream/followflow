<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FollowUp extends Model {
    protected $fillable = ['lead_id','method','message','sent_at'];
    public $timestamps = false;

    public function lead(): BelongsTo { return $this->belongsTo(Lead::class); }
}
