<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Application extends Model {
	//public $timestamps = false;
	protected $guarded = array('id');

	public function application_details() {
		return $this->hasMany(ApplicationDetail::class, 'application_id');
	}
	public function user() {
		return $this->belongsTo(User::class);
	}
	public function employee() {
		return $this->belongsTo(Employee::class);
	}
	public function distributor() {
		return $this->belongsTo(Distributor::class, 'distributor_id');
	}
	public function region() {
		return $this->belongsTo(Region::class, 'region_id');
	}
	public function depot() {
		return $this->belongsTo(Depot::class, 'depot_id');
	}
	public function mdrInformation() {
		return $this->hasMany(MdrInformation::class);
	}
	
}
