<?php

namespace App;
use App\User;

use Illuminate\Database\Eloquent\Model;

class TadaReportingSequence extends Model
{
    protected $dates = ['created_at', 'updated_at'];
	protected $guarded = array('id');

	public function tada_reportingsequence_details() {
		return $this->hasMany(TadaReportingSequenceDetail::class);
	}
	public function user() {
		return $this->belongsTo(User::class);
	}
		 
	 
}

