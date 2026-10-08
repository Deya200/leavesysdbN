<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffVoicePollOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_voice_entry_id',
        'label',
        'votes',
    ];

    public function entry()
    {
        return $this->belongsTo(StaffVoiceEntry::class, 'staff_voice_entry_id');
    }
}
