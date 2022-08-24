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
        Schema::create('class_topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discipline_id')->constrained()->cascadeOnDelete();
            $table->text('title');
            $table->text('description')->nullable();
            $table->smallInteger('duration')->default(2);
            $table->integer('sort_order')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('class_topics');
    }
};
