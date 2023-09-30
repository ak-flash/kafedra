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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('document_id')->nullable()->unique();
            $table->string('name')->nullable();
            $table->string('last_name');
            $table->foreignId('group_id')->constrained();
            $table->string('email')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone', 15)->nullable()->unique();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->boolean('active')->default(true);
            $table->boolean('chief')->default(false);
            $table->text('profile_photo_path')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
