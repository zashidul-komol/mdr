<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Distributor extends Model
{
    //protected $fillable  ['name', 'short_name', 'status']; 
    public $timestamps = false;
    protected $guarded = array('id');

    public function region() {
        return $this->belongsTo(Region::class, 'region_id');
    }
    public function depot() {
        return $this->belongsTo(Depot::class, 'depot_id');
    }

}
