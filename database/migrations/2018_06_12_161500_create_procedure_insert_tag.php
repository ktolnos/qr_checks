<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcedureInsertTag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        DROP PROCEDURE IF EXISTS INSERT_TAG;
        CREATE PROCEDURE INSERT_TAG(IN new_name VARCHAR(50), IN parent_name VARCHAR(50))
        BEGIN
            START TRANSACTION;

            SET @myRight := null;
            if (not (parent_name is NULL )) then
            SELECT @myRight := rgt FROM tags
              WHERE name = parent_name
              FOR UPDATE;
                  end if;

            if (@myRight is NULL) then
              SELECT @myRight := max(rgt)+2 FROM tags
              FOR UPDATE;
                  end if;

            if (@myRight is NULL) then
              SET @myRight = 1;
            end if;

            UPDATE tags SET rgt = rgt + 2 WHERE rgt >= @myRight;
            UPDATE tags SET lft = lft + 2 WHERE lft >= @myRight;
            INSERT INTO tags(name, lft, rgt) VALUES(new_name, @myRight, @myRight + 1);

            COMMIT;
          END;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS INSERT_TAG');
    }
}
