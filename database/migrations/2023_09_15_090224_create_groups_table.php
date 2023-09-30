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
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('number');
            $table->foreignId('faculty_id')->constrained()->cascadeOnDelete();
            $table->integer('course_number')->nullable();
            $table->smallInteger('year')->default(now()->format('Y'));
            $table->text('description')->nullable();
            $table->unique(['number', 'faculty_id', 'course_number', 'year']);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
