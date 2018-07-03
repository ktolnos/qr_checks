<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggrtTagsAfterUpdateChangeProductTags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        create trigger tags_after_update_change_product_tags
  after UPDATE
  on tags
  for each row
  BEGIN
    UPDATE product_tag set tag_name = NEW.name where tag_name = OLD.name;
  END;

');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DRoP trigger IF EXISTS tags_after_update_change_product_tags');
    }
}
