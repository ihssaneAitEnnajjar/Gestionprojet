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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sprint_id')->default(0); // Replace 0 with the appropriate default value
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('due_date');
            $table->unsignedBigInteger('user_id');
            $table->string('status')->nullable();
            $table->timestamps();

           
            // If you have a `users` table for managers, uncomment the following line:
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
