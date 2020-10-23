<?php namespace Tatter\Outbox\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOutboxTemplates extends Migration
{
	public function up()
	{
		$fields = [
			'name'       => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
			'subject'    => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
			'body'       => ['type' => 'text', 'null' => true],
			'tokens'     => ['type' => 'text', 'null' => true],
			'created_at' => ['type' => 'datetime', 'null' => true],
			'updated_at' => ['type' => 'datetime', 'null' => true],
			'deleted_at' => ['type' => 'datetime', 'null' => true],
		];
		
		$this->forge->addField('id');
		$this->forge->addField($fields);

		$this->forge->addKey('name');
		$this->forge->addKey('created_at');
		
		$this->forge->createTable('outbox_templates');
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$this->forge->dropTable('outbox_templates');
	}
}
