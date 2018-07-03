<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcedureDeleteTag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        DROP procedure if exists DELETE_TAG;
        create procedure DELETE_TAG(IN nameToDelete varchar(50))
        BEGIN
            START TRANSACTION;

            SELECT @myLeft := lft, @myRight := rgt, @myWidth := rgt - lft + 1
            FROM tags
            WHERE name = nameToDelete
            FOR UPDATE;
            if (@myRight is NOT NULL) then
                  DELETE FROM tags WHERE lft BETWEEN @myLeft AND @myRight;
                  UPDATE tags SET rgt = rgt - @myWidth WHERE rgt > @myRight;
                  UPDATE tags SET lft = lft - @myWidth WHERE lft > @myRight;
            end if;


            COMMIT;
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
        DB::unprepared('DROP PROCEDURE IF EXISTS DELETE_TAG');
    }
}
