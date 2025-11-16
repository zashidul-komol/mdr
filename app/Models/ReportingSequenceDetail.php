<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportingSequenceDetail extends Model
{
    public $timestamps = false;
	protected $guarded = array('id');

	public function reportingsequences() {
		return $this->belongsTo(ReportingSequence::class);
	}
	public function user() {
		return $this->hasMany(User::class);
	}


	 
	 
}

