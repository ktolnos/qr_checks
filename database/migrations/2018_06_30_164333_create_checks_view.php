<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChecksView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        DROP VIEW IF EXISTS checks_view;
        create view checks_view as select
              c.id,
              c.initialTotalSum,
              c.fiscalSign,
              c.fiscalDocumentNumber,
              c.fiscalDriveNumber,
              sum(p.sum) as actualTotalSum,
              c.initialDate, s.name as storeName,
              c.created_at,
              c.updated_at
            from checks c
            left join stores s on s.inn = c.storeInn
            left join products p on p.check_id = c.id
            group by c.id
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
            'DROP VIEW IF EXISTS users_view'
        );
    }
}
