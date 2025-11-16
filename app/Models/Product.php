<?php

namespace App\Models;
use App\Models\Department;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Section;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
     //public $timestamps = false;
	protected $guarded = array('id');

	public function department() {
        return $this->belongsTo(Department::class,'department_id');
    }
    public function category() {
        return $this->belongsTo(Category::class,'category_id');
    }
    public function subcategory() {
        return $this->belongsTo(Subcategory::class,'subcategory_id');
    }
    public function section() {
        return $this->belongsTo(Section::class,'section_id');
    }
    public function user() {
        return $this->hasOne(User::class);
    }
	 
}
