<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationLog extends Model
{
    public $timestamps = false;
    protected $guarded = array('id');

    public function application() {
        return $this->belongsTo(Application::class);
    }
    public function employee() {
        return $this->belongsTo(Employee::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
}
