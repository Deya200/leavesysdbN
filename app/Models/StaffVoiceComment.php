<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffVoiceComment extends Model
{
    protected $fillable = ['employee_number', 'author_name', 'body'];

    public function entry()
    {
        return $this->belongsTo(StaffVoiceEntry::class, 'staff_voice_entry_id');
    }
}
