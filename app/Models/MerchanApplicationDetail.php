<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchanApplicationDetail extends Model
{
    public $timestamps = false;
    protected $guarded = array('id');

    public function department() {
        return $this->belongsTo(Department::class);
    }
    public function employee() {
        return $this->belongsTo(Employee::class);
    }
    public function user() {
        return $this->hasMany(User::class);
    }
    public function merchan_applications() {
        return $this->belongsTo(MerchanApplication::class, 'id');
    }
}
