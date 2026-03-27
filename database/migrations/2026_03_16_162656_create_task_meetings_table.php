<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('task_meetings', function (Blueprint $table) {
            $table->id();
            $table->string('meeting_no')->unique(); 
            $table->foreignId('assigned_task_id');
            $table->json('users');
            $table->text('meet_link');
            $table->text('meeting_message')->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_meetings');
    }
};
