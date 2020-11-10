<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageThread extends Model
{
    use HasFactory;

    public function threadMembers()
    {
        return $this->hasMany('App\Models\MessageThreadMember');
    }

    public function threadGroupDetails()
    {
        return $this->hasOne('App\Models\MessageThreadGroupDetail');
    }

    public function threadMessages()
    {
        return $this->hasMany('App\Models\MessageThreadMessages');
    }
}
