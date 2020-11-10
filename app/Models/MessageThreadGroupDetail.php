<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageThreadGroupDetail extends Model
{
    use HasFactory;

    public function messageThread()
    {
        return $this->belongsTo('App\Models\MessageThread');
    }
}
