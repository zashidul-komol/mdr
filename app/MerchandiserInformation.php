<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MerchandiserInformation extends Model
{
    public $timestamps = false;
    protected $guarded = array('id');

    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function depots() {
        return $this->belongsTo(Depot::class, 'depot_id');
    }
    public function mdrAttendances() {
        return $this->hasMany(MdrAttendance::class, 'merchan_id');
    }
        
}
