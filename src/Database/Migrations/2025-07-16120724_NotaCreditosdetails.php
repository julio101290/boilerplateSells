<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class NotaCreditosdetails extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'idNotaCredito' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'idProduct' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'lote' => ['type' => 'VARCHAR', 'constraint' => 64, 'null' => true],
            'idAlmacen' => ['type' => 'BIGINT', 'constraint' => 20, 'null' => true],
            'description' => ['type' => 'VARCHAR', 'constraint' => 512, 'null' => true],
            'claveProductoSAT' => ['type' => 'VARCHAR', 'constraint' => 64, 'null' => true],
            'claveUnidadSAT' => ['type' => 'VARCHAR', 'constraint' => 64, 'null' => true],
            'codeProduct' => ['type' => 'VARCHAR', 'constraint' => 32, 'null' => true],
            'cant' => ['type' => 'DECIMAL', 'constraint' => '18,6', 'null' => true],
            'price' => ['type' => 'DECIMAL', 'constraint' => '18,6', 'null' => true],
            'porcentTax' => ['type' => 'DECIMAL', 'constraint' => '18,6', 'null' => true],
            'tax' => ['type' => 'DECIMAL', 'constraint' => '18,6', 'null' => true],
            'porcentIVARetenido' => ['type' => 'DECIMAL', 'constraint' => '18,6', 'null' => true],
            'IVARetenido' => ['type' => 'DECIMAL', 'constraint' => '18,6', 'null' => true],
            'porcentISRRetenido' => ['type' => 'DECIMAL', 'constraint' => '18,6', 'null' => true],
            'ISRRetenido' => ['type' => 'DECIMAL', 'constraint' => '18,6', 'null' => true],
            'neto' => ['type' => 'DECIMAL', 'constraint' => '18,6', 'null' => true],
            'total' => ['type' => 'DECIMAL', 'constraint' => '18,6', 'null' => true],
            'unidad' => ['type' => 'VARCHAR', 'constraint' => 64, 'null' => true],
            'tasaCero' => ['type' => 'VARCHAR', 'constraint' => 16, 'null' => true],
            'importeExento' => ['type' => 'DECIMAL', 'constraint' => '18,6', 'null' => true],
            'predial' => ['type' => 'VARCHAR', 'constraint' => 32, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('notacreditodetails', true);
    }

    public function down()
    {
        $this->forge->dropTable('notacreditodetails', true);
    }
}
