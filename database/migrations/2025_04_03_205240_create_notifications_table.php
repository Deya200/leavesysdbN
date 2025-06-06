<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('EmployeeNumber'); // Foreign key to employees table
            $table->text('Message'); // Notification message
            $table->string('Status')->default('Unread'); // Status (Unread/Read)
            $table->timestamps(); // created_at and updated_at columns

            // Add foreign key constraint (optional)
            $table->foreign('EmployeeNumber')
                  ->references('EmployeeNumber')
                  ->on('employees')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
