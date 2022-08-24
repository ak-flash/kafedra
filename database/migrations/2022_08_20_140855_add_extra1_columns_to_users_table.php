<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 15)->nullable()->unique();
            $table->boolean('show_phone')->default(false);
            $table->boolean('active')->default(true);
            $table->text('profile_photo_path')->nullable();
            $table->date('birth_date')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone_number', 'active', 'profile_photo_path', 'birth_date');
            $table->dropSoftDeletes();
        });
    }
};
