<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerProductsAfterInsert extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('create trigger products_after_insert_create_portions
  after INSERT
  on products
  for each row
  BEGIN

    DECLARE finished INTEGER DEFAULT 0;
    DECLARE id_user INTEGER DEFAULT 0;

    DECLARE users_cursor CURSOR FOR SELECT users.id from users;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET finished = 1;
    OPEN users_cursor;
    get_users: loop
      fetch users_cursor into id_user;
      IF finished = 1 THEN
         LEAVE get_users;
      END IF;
      INSERT into product_user (product_id, user_id, portion) VALUES (NEW.id, id_user, 1);
    end loop;
    CLOSE users_cursor;
  END;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER if exists products_after_insert_create_portions');
    }
}
