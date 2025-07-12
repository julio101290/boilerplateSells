<?php
// Adaptado por julio101290

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterSellsDetailsDecimalPrecision extends Migration
{
    public function up()
    {
        $fields = [
            'cant' => [
                'name'       => 'cant',
                'type'       => 'DECIMAL',
                'constraint' => '18,5',
                'null'       => true,
            ],
            'price' => [
                'name'       => 'price',
                'type'       => 'DECIMAL',
                'constraint' => '18,5',
                'null'       => true,
            ],
            'porcentTax' => [
                'name'       => 'porcentTax',
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
                'null'       => true,
            ],
            'tax' => [
                'name'       => 'tax',
                'type'       => 'DECIMAL',
                'constraint' => '18,2',
                'null'       => true,
            ],
            'porcentIVARetenido' => [
                'name'       => 'porcentIVARetenido',
                'type'       => 'DECIMAL',
                'constraint' => '18,4',
                'null'       => true,
            ],
            'IVARetenido' => [
                'name'       => 'IVARetenido',
                'type'       => 'DECIMAL',
                'constraint' => '18,4',
                'null'       => true,
            ],
            'porcentISRRetenido' => [
                'name'       => 'porcentISRRetenido',
                'type'       => 'DECIMAL',
                'constraint' => '18,4',
                'null'       => true,
            ],
            'ISRRetenido' => [
                'name'       => 'ISRRetenido',
                'type'       => 'DECIMAL',
                'constraint' => '18,4',
                'null'       => true,
            ],
            'neto' => [
                'name'       => 'neto',
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
            'importeExento' => [
                'name'       => 'importeExento',
                'type'       => 'DECIMAL',
                'constraint' => '18,4',
                'null'       => true,
            ],
        ];

        $this->forge->modifyColumn('sellsdetails', $fields);
    }

    public function down()
    {
        $fields = [
            'cant' => ['name' => 'cant', 'type' => 'DECIMAL', 'constraint' => '18', 'null' => true],
            'price' => ['name' => 'price', 'type' => 'DECIMAL', 'constraint' => '18', 'null' => true],
            'porcentTax' => ['name' => 'porcentTax', 'type' => 'DECIMAL', 'constraint' => '18', 'null' => true],
            'tax' => ['name' => 'tax', 'type' => 'DECIMAL', 'constraint' => '18', 'null' => true],
            'porcentIVARetenido' => ['name' => 'porcentIVARetenido', 'type' => 'DECIMAL', 'constraint' => '18', 'null' => true],
            'IVARetenido' => ['name' => 'IVARetenido', 'type' => 'DECIMAL', 'constraint' => '18', 'null' => true],
            'porcentISRRetenido' => ['name' => 'porcentISRRetenido', 'type' => 'DECIMAL', 'constraint' => '18', 'null' => true],
            'ISRRetenido' => ['name' => 'ISRRetenido', 'type' => 'DECIMAL', 'constraint' => '18', 'null' => true],
            'neto' => ['name' => 'neto', 'type' => 'DECIMAL', 'constraint' => '18', 'null' => true],
            'total' => ['name' => 'total', 'type' => 'DECIMAL', 'constraint' => '18', 'null' => true],
            'importeExento' => ['name' => 'importeExento', 'type' => 'DECIMAL', 'constraint' => '18', 'null' => true],
        ];

        $this->forge->modifyColumn('sellsdetails', $fields);
    }
}
