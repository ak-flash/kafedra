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
        Schema::table('class_topics', function (Blueprint $table) {
            $table->smallInteger('semester')->default(1);

            $table->unsignedBigInteger('last_edited_by_id')->nullable()->after('user_id');
            $table->foreign('last_edited_by_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('class_topics', function (Blueprint $table) {
            $table->dropColumn(['semester', 'last_edited_by_id']);
        });
    }
};
