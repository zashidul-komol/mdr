<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchanApplication extends Model {
	//public $timestamps = false;
	protected $guarded = array('id');

	public function merchan_application_details() {
		return $this->hasMany(MerchanApplicationDetail::class, 'merchan_application_id');
	}
	public function merchan_application_logs() {
		return $this->hasMany(MerchanApplicationLog::class, 'merchan_application_id');
	}
	public function user() {
		return $this->belongsTo(User::class);
	}
	public function employee() {
		return $this->belongsTo(Employee::class);
	}
	public function depot() {
		return $this->belongsTo(Depot::class, 'depot_id');
	}
	public function merchandiser_informations() {
		return $this->hasMany(MerchandiserInformation::class);
	}
	
}
