<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    protected $guarded = ['id'];

    public function getFullnameAttribute()
    {
        return $this->name;
    }

    public function getUsernameAttribute()
    {
        return $this->email;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
    
    public function fsp()
    {
        return $this->belongsTo(Fsp::class);
    }

    public function supportMessage(){
        return $this->hasMany(SupportMessage::class);
    }

}
