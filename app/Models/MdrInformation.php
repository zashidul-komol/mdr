<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MdrInformation extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $table = 'mdr_informations';


    //protected $guarded = array('id');

    public function distributors() {
        return $this->belongsTo(Distributor::class, 'distributor_id');
    }
    public function regions() {
        return $this->belongsTo(Region::class, 'region_id');
    }
    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function depots() {
        return $this->belongsTo(Depot::class, 'depot_id');
    }
    public function mdrAttendances() {
        return $this->hasMany(MdrAttendance::class, 'mdr_id');
    }
        
}
