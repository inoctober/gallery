<?php namespace Increative\Gallery\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateGalleriesTable extends Migration
{

    public function up()
    {
        Schema::create('increative_gallery_galleries', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('increative_gallery_galleries');
    }

}
