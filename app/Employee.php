<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    //protected $fillable  ['name', 'short_name', 'status']; 
    public $timestamps = false;
	protected $guarded = array('id');

	public function designation() {
        return $this->belongsTo(Designation::class,'designation_id');
    }

 	public function department() {
        return $this->belongsTo(Department::class,'department_id');
    }
    
    public function office_location() {
        return $this->belongsTo(OfficeLocation::class, 'officelocation_id');
    }

    public function section() {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function region() {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function user() {
        return $this->hasOne(User::class);
    }
	 
	 
}

