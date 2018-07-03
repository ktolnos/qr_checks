<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        DROP VIEW IF EXISTS users_view;
            create view users_view as select t.id, t.name, t.brought, t.spent, t.brought-t.spent as balance from (
              select
                u.id,
                u.name,
                COALESCE((sum(payer_p.amount) - COALESCE(sum(payee_p.amount), 0)), 0) as brought,
                COALESCE(spent_table.spent, 0) as spent
              from users as u
                left join payments as payer_p on u.id = payer_p.payer_id
                left join payments as payee_p on u.id = payee_p.payee_id
                left join (
                            select
                              p_u.user_id,
                              sum(p_u.portion * p.sum /
                                  (select sum(p_u2.portion)
                                   from product_user p_u2
                                   where p_u.product_id = p_u2.product_id)
                              ) as spent
                            from
                              product_user p_u
                              join products p on p_u.product_id = p.id
                            group by p_u.user_id
                          ) as spent_table
                  on u.id = spent_table.user_id
              group by u.id
            ) t;
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
