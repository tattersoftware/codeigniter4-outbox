<?php namespace Tatter\Outbox\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEmailsTemplate extends Migration
{
	public function up()
	{
		$this->forge->addColumn('outbox_emails', [
			'template_id' => ['type' => 'int', 'null' => true, 'after' => 'BCCBatchSize'],
			'template'    => ['type' => 'varchar', 'constraint' => 255, 'null' => true, 'after' => 'BCCBatchSize'],
		]);
	}

	public function down()
	{
		$this->forge->dropColumn('outbox_emails', 'template');
		$this->forge->dropColumn('outbox_emails', 'template_id');
	}
}
