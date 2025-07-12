<?php
// Adaptado por julio101290

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterSellsDecimalPrecision extends Migration
{
    public function up()
    {
        $fields = [
            'taxes' => [
                'name'       => 'taxes',
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
                'null'       => true,
            ],
            'subTotal' => [
                'name'       => 'subTotal',
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
                'null'       => true,
            ],
            'total' => [
                'name'       => 'total',
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
                'null'       => true,
            ],
            'balance' => [
                'name'       => 'balance',
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
                'null'       => true,
            ],
            'IVARetenido' => [
                'name'       => 'IVARetenido',
                'type'       => 'DECIMAL',
                'constraint' => '18,4',
                'null'       => false,
            ],
            'ISRRetenido' => [
                'name'       => 'ISRRetenido',
                'type'       => 'DECIMAL',
                'constraint' => '18,4',
                'null'       => false,
            ],
            'tasaCero' => [
                'name'       => 'tasaCero',
                'type'       => 'DECIMAL',
                'constraint' => '18,4',
                'null'       => true,
            ],
        ];

        $this->forge->modifyColumn('sells', $fields);
    }

    public function down()
    {
        $fields = [
            'taxes' => ['name' => 'taxes', 'type' => 'DECIMAL', 'constraint' => '18', 'null' => true],
            'subTotal' => ['name' => 'subTotal', 'type' => 'DECIMAL', 'constraint' => '18', 'null' => true],
            'total' => ['name' => 'total', 'type' => 'DECIMAL', 'constraint' => '18', 'null' => true],
            'balance' => ['name' => 'balance', 'type' => 'DECIMAL', 'constraint' => '18', 'null' => true],
            'IVARetenido' => ['name' => 'IVARetenido', 'type' => 'DECIMAL', 'constraint' => '18', 'null' => false],
            'ISRRetenido' => ['name' => 'ISRRetenido', 'type' => 'DECIMAL', 'constraint' => '18', 'null' => false],
            'tasaCero' => ['name' => 'tasaCero', 'type' => 'DECIMAL', 'constraint' => '18', 'null' => true],
        ];

        $this->forge->modifyColumn('sells', $fields);
    }
}
