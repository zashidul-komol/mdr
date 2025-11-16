<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model {
	//public $timestamps = false;
	protected $guarded = array('id');

	public function user() {
		return $this->belongsTo(User::class);
	}
	public function regions() {
		return $this->belongsTo(Region::class, 'region_id');
	}
	public function depots() {
		return $this->belongsTo(Depot::class, 'depot_id');
	}
	public function months() {
		return $this->belongsTo(Month::class, 'month_id');
	}
	
	
}
