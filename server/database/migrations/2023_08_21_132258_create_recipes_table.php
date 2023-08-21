<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('cuisine_id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('recipe_ingredients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('recipe_id');
            $table->unsignedBigInteger('ingredient_id');
            $table->string('amount');
            $table->timestamps();
        });
        
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('recipe_id');
            $table->string('image_url');
            $table->timestamps();
        });
        
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('amount');
            $table->timestamps();
        });

        Schema::create('cuisines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });


        Schema::table('recipes', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('cuisine_id')->references('id')->on('cuisines')->onDelete('cascade');
        });

        Schema::table('recipe_ingredients', function (Blueprint $table) {
            $table->foreign('recipe_id')->references('id')->on('recipes')->onDelete('cascade');
            $table->foreign('ingredient_id')->references('id')->on('ingredients')->onDelete('cascade');
        });

        Schema::table('images', function (Blueprint $table) {
            $table->foreign('recipe_id')->references('id')->on('recipes')->onDelete('cascade');
        });

    }

    public function down(): void
    {
        Schema::table('recipes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['cuisine_id']);
        });

        Schema::table('recipe_ingredients', function (Blueprint $table) {
            $table->dropForeign(['recipe_id']);
            $table->dropForeign(['ingredient_id']);
        });

        Schema::table('images', function (Blueprint $table) {
            $table->dropForeign(['recipe_id']);
        });

        // drop tables
        Schema::dropIfExists('recipes');
        Schema::dropIfExists('recipe_ingredients');
        Schema::dropIfExists('images');
        Schema::dropIfExists('cuisines');
        Schema::dropIfExists('ingredients');
    }
};