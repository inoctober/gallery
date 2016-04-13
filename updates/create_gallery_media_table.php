<?php namespace Increative\Gallery\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateGalleryMediaTable extends Migration
{

    public function up()
    {
        Schema::create('increative_gallery_gallery_media', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('gallery_id');
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->text('media_url');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('increative_gallery_gallery_media');
    }

}
