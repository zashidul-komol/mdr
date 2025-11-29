<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TadaReportingSequenceDetail extends Model
{
    public $timestamps = false;
	protected $guarded = array('id');
	protected $table = 'tada_reporting_sequence_details';

	public function tada_reportingsequences() {
		return $this->belongsTo(TadaReportingSequence::class);
	}
	public function user() {
		return $this->hasMany(User::class);
	}


	 
	 
}

