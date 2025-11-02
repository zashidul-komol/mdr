<?php

namespace App;
use App\Department;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    //protected $fillable  ['name', 'short_name', 'status']; 
    public $timestamps = false;
	protected $guarded = array('id');


	public function department() {
        return $this->belongsTo(Department::class,'department_id');
    }
 
	 
}

