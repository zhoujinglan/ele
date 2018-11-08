<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventPrize extends Model
{
    //
    protected $fillable=["name",'event_id','description','user_id'];

    public function user(  ){
        return $this->belongsTo(User::class,"user_id");
    }

    public function event(  ){
        return $this->belongsTo(Event::class,"event_id");

    }
}
