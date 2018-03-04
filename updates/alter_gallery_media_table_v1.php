<?php namespace Increative\Gallery\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AlterGalleryMediaTableV1 extends Migration
{

    public function up()
    {
        Schema::table('increative_gallery_gallery_media', function($table)
        {
            $table->string('folder', 255);
            $table->text('path');
        });
    }

    public function down()
    {
        Schema::table('increative_gallery_gallery_media', function($table)
        {
            $table->dropColumn('folder');
            $table->dropColumn('path');
        });
    }

}
