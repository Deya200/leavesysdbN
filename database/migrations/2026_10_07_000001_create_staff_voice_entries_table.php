<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff_voice_entries', function (Blueprint $table) {
            $table->id();
            $table->string('type', 40);
            $table->string('title')->nullable();
            $table->text('body');
            $table->string('author_name')->nullable();
            $table->string('department')->nullable();
            $table->string('recipient')->nullable();
            $table->string('category')->nullable();
            $table->string('status', 40)->default('Submitted');
            $table->string('reference_code', 32)->nullable()->unique();
            $table->unsignedInteger('votes')->default(0);
            $table->json('meta')->nullable();
            $table->string('submitted_by')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->boolean('is_public')->default(true);
            $table->text('response')->nullable();
            $table->string('responded_by')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();

            $table->index(['type', 'status']);
            $table->index('submitted_by');
            $table->index('is_public');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_voice_entries');
    }
};
