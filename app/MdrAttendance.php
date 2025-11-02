<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MdrAttendance extends Model
{
    public $timestamps = false;
    protected $guarded = array('id');

    public function distributors() {
        return $this->belongsTo(Distributor::class, 'distributor_id');
    }
    public function employee() {
        return $this->belongsTo(Employee::class);
    }
    public function mdrInformations() {
        return $this->belongsTo(MdrInformation::class, 'mdr_id');
    }
    public function depots() {
        return $this->belongsTo(Depot::class, 'depot_id');
    }
    public function regions() {
        return $this->belongsTo(Region::class, 'region_id');
    }
    public function months() {
        return $this->belongsTo(Month::class, 'month_id');
    }
    public function attendances() {
        return $this->belongsTo(Attendance::class, 'attendance_id');
    }
    public function merchandiser_informations() {
        return $this->belongsTo(MerchandiserInformation::class, 'merchan_id');
    }

    
}
