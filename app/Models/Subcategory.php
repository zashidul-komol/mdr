<?php

namespace App\Models;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    //protected $fillable  ['name', 'short_name', 'status']; 
    public $timestamps = false;
	protected $guarded = array('id');

	public function category() {
        return $this->belongsTo(Category::class,'category_id');
    }

	 
	 
}

