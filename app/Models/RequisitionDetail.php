<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequisitionDetail extends Model
{
    public $timestamps = false;
    protected $guarded = array('id');

    public function requisition() {
        return $this->belongsTo(Requisition::class);
    }
    public function product() {
        return $this->belongsTo(Product::class);
    }
    public function department() {
        return $this->belongsTo(Department::class);
    }
    public function section() {
        return $this->belongsTo(Section::class);
    }
    public function machine() {
        return $this->belongsTo(Machine::class);
    }
    public function vehicle() {
        return $this->belongsTo(Vahicle::class);
    }
    public function employee() {
        return $this->belongsTo(Employee::class);
    }
    public function measurement() {
        return $this->belongsTo(Measurement::class);
    }
    public function user() {
        return $this->hasMany(User::class);
    }
}
