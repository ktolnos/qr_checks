<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComputedTagTreeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        DROP VIEW IF EXISTS computed_tag_tree;
            create view computed_tag_tree as SELECT node.name as name, (COUNT(parent.name) - 1) AS depth, (SELECT name
                       FROM tags t2
                       WHERE t2.lft < node.lft AND t2.rgt > node.rgt
                       ORDER BY t2.rgt-node.rgt ASC LIMIT 1) AS parent_name
            FROM tags AS node,
                    tags AS parent
            WHERE node.lft BETWEEN parent.lft AND parent.rgt
            GROUP BY node.name
            order by max(node.lft)
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared(
            'DROP VIEW IF EXISTS computed_tag_tree'
        );
    }
}
