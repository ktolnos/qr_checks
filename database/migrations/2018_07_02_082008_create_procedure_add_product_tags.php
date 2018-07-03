<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcedureAddProductTags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('DRoP PROCEDURE IF EXISTS ADD_TAGS_TO_PRODUCTS;
CREATE PROCEDURE ADD_TAGS_TO_PRODUCTS()
  BEGIN
    DECLARE finished INTEGER DEFAULT 0;
    DECLARE product_name VARCHAR(255);
    DECLARE name_tag VARCHAR(255);

    DECLARE pt_cursor CURSOR FOR SELECT DISTINCT p.name, pt.tag_name from products p
      join product_tag pt on p.id = pt.product_id;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET finished = 1;
    OPEN pt_cursor;
    get_pt: loop
      fetch pt_cursor into product_name, name_tag;
      IF finished = 1 THEN
         LEAVE get_pt;
      END IF;
      BLOCK_PRODUCTS: begin

        DECLARE finished2 INTEGER DEFAULT 0;
        DECLARE id_product INTEGER DEFAULT 0;

        DECLARE product_cursor CURSOR FOR SELECT id
          FROM products where name = product_name;

        DECLARE CONTINUE HANDLER FOR NOT FOUND SET finished2 = 1;
        open product_cursor;
        get_product: loop
           fetch product_cursor into id_product;

           IF finished2 = 1 THEN
             LEAVE get_product;
           END IF;
           INSERT into product_tag (product_id, tag_name) VALUES (id_product, name_tag) ON DUPLICATE KEY UPDATE product_id = product_id;
        end loop;
        close product_cursor;
      end BLOCK_PRODUCTS;
    end loop;
    CLOSE pt_cursor;
  END;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DRoP PROCEDURE IF EXISTS ADD_TAGS_TO_PRODUCTS;');
    }
}
