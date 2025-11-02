<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MdrAttendanceLog extends Model {
	//public $timestamps = false;
	protected $guarded = array('id');

	public function user() {
		return $this->belongsTo(User::class);
	}
	public function attendances() {
		return $this->belongsTo(Attendance::class, 'attendance_id');
	}
	

}
