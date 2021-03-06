<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShortnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shortners', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url_long');
            $table->integer('clicks')->default(0);
            $table->date('expire');

			$table->timestamp('updated_at')->nullable();
			$table->timestamp('created_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shortener');
    }
}
