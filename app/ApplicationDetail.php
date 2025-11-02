<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApplicationDetail extends Model
{
    public $timestamps = false;
    protected $guarded = array('id');

    public function distributors() {
        return $this->belongsTo(Distributor::class, 'id');
    }
    public function department() {
        return $this->belongsTo(Department::class);
    }
    public function employee() {
        return $this->belongsTo(Employee::class);
    }
    public function user() {
        return $this->hasMany(User::class);
    }
    public function applications() {
        return $this->belongsTo(Application::class, 'id');
    }
}
