<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff_voice_poll_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_voice_entry_id')->constrained('staff_voice_entries')->cascadeOnDelete();
            $table->string('label');
            $table->unsignedInteger('votes')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_voice_poll_options');
    }
};
