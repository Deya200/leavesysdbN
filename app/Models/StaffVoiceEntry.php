<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffVoiceEntry extends Model
{
    use HasFactory;

    public const TYPES = [
        'idea',
        'recognition',
        'poll',
        'question',
        'help',
        'grievance',
        'report',
    ];

    protected $fillable = [
        'type',
        'title',
        'body',
        'author_name',
        'department',
        'recipient',
        'category',
        'status',
        'reference_code',
        'votes',
        'meta',
        'submitted_by',
        'is_anonymous',
        'is_public',
        'response',
        'responded_by',
        'responded_at',
    ];

    protected $casts = [
        'meta' => 'array',
        'is_anonymous' => 'boolean',
        'is_public' => 'boolean',
        'responded_at' => 'datetime',
    ];

    public function pollOptions()
    {
        return $this->hasMany(StaffVoicePollOption::class);
    }

    public function getDisplayAuthorAttribute(): string
    {
        if ($this->is_anonymous) {
            return 'Anonymous';
        }

        return $this->author_name ?: 'Staff member';
    }

    public function getStatusToneAttribute(): string
    {
        return match ($this->status) {
            'In Progress', 'Contact Arranged', 'In Mediation', 'Under Review' => 'warning',
            'Implemented', 'Resolved', 'Closed' => 'success',
            default => 'secondary',
        };
    }
}
