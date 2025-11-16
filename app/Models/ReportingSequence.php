<?php

namespace App\Models;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;

class ReportingSequence extends Model
{
    protected $dates = ['created_at', 'updated_at'];
	protected $guarded = array('id');

	public function reportingsequence_details() {
		return $this->hasMany(ReportingSequenceDetail::class);
	}
	public function user() {
		return $this->belongsTo(User::class);
	}
		 
	 
}

