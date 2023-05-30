<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*

- name
- image
- price
- description
- has_demo
- release_date
*/

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->text('image')->nullable();
            $table->float('price', unsigned: true)->nullable();
            $table->text('description')->nullable();
            $table->string('platform')->default('nintendo switch');
            $table->boolean('has_demo')->default(false);
            $table->date('release_date')->nullable();
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
        Schema::dropIfExists('games');
    }
};
