<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TadaReportingSequenceDetail extends Model
{
    public $timestamps = false;
	protected $guarded = array('id');

	public function tada_reportingsequences() {
		return $this->belongsTo(TadaReportingSequence::class);
	}
	public function user() {
		return $this->hasMany(User::class);
	}


	 
	 
}

