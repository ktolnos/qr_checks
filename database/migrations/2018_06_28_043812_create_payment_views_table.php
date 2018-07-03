<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        DROP VIEW IF EXISTS payments_view;
            create view payments_view as select
                                           payments.id,
                                           payments.payer_id,
                                           payer.name as payer_name,
                                           payments.payee_id,
                                           payee.name as payee_name,
                                           payments.check_id,
                                           c.initialDate as check_date,
                                           payments.amount,
                                           payments.created_at
                                         from payments
              inner join users as payer on payments.payer_id = payer.id
              left join users as payee on payments.payee_id = payee.id
              left join checks c on payments.check_id = c.id
              order by payments.created_at desc
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
            'DROP VIEW IF EXISTS payments_view'
        );
    }
}
