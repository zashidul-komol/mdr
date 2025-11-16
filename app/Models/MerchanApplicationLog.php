<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchanApplicationLog extends Model
{
    public $timestamps = false;
    protected $guarded = array('id');

    public function merchan_applications() {
        return $this->belongsTo(MerchanApplication::class, 'id');
    }
    public function employee() {
        return $this->belongsTo(Employee::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
}
