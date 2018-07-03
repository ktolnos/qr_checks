<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerUsersAfterInsertAddProducts extends Migration
{
    public function up()
    {
        DB::unprepared('drop trigger if exists users_after_insert_create_portions;
create trigger users_after_insert_create_portions
  after INSERT
  on users
  for each row
  BEGIN

    DECLARE finished INTEGER DEFAULT 0;
    DECLARE id_product INTEGER DEFAULT 0;
    DECLARE name_tag VARCHAR(255);

    DECLARE products_cursor CURSOR FOR SELECT products.id from products;
    DECLARE tags_cursor CURSOR FOR SELECT tags.name from tags;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET finished = 1;
    OPEN products_cursor;
    get_products: loop
      fetch products_cursor into id_product;
      IF finished = 1 THEN
         LEAVE get_products;
      END IF;
      INSERT into product_user (product_user.product_id, product_user.user_id, product_user.portion) VALUES (id_product, NEW.id, 0.0);
    end loop;
    CLOSE products_cursor;
    OPEN tags_cursor;
    get_tags: loop
      fetch tags_cursor into name_tag;
      IF finished = 1 THEN
         LEAVE get_tags;
      END IF;
      INSERT into tag_user (tag_user.tag_name, tag_user.user_id, tag_user.portion) VALUES (name_tag, NEW.id, 0.0);
    end loop;
    CLOSE tags_cursor;
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
        DB::unprepared('DROP TRIGGER if exists users_after_insert_create_portions');
    }
}
