<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TadaReportingSequence extends Model
{
    protected $dates = ['created_at', 'updated_at'];
	protected $guarded = array('id');
	protected $table = 'tada_reporting_sequences';

	public function tada_reportingsequence_details() {
		return $this->hasMany(TadaReportingSequenceDetail::class);
	}
	public function user() {
		return $this->belongsTo(User::class);
	}
		 
	 
}

