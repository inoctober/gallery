<?php namespace Increative\Gallery\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AlterGalleriesTableV1 extends Migration
{

    public function up()
    {
        Schema::table('increative_gallery_galleries', function($table)
        {
            $table->string('code', 255)->unique()->nullable();
            $table->text('featured_image')->nullable();
            $table->integer('parent_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('increative_gallery_galleries', function($table)
        {
            $table->dropColumn('code');
            $table->dropColumn('featured_image');
            $table->dropColumn('parent_id');
        });
    }

}
