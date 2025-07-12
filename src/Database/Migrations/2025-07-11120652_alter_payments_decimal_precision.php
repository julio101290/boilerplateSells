<?php
// Adaptado por julio101290

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterPaymentsDecimalPrecision extends Migration
{
    public function up()
    {
        $fields = [
            'importPayment' => [
                'name'       => 'importPayment',
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
                'null'       => false,
            ],
            'importBack' => [
                'name'       => 'importBack',
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
                'null'       => false,
            ],
        ];

        $this->forge->modifyColumn('payments', $fields);
    }

    public function down()
    {
        $fields = [
            'importPayment' => [
                'name'       => 'importPayment',
                'type'       => 'DECIMAL',
                'constraint' => '18',
                'null'       => false,
            ],
            'importBack' => [
                'name'       => 'importBack',
                'type'       => 'DECIMAL',
                'constraint' => '18',
                'null'       => false,
            ],
        ];

        $this->forge->modifyColumn('payments', $fields);
    }
}
