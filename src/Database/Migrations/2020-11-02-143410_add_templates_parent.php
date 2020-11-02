<?php namespace Tatter\Outbox\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTemplatesParent extends Migration
{
	public function up()
	{
		$this->forge->addColumn('outbox_templates', [
			'parent_id' => ['type' => 'int', 'null' => true, 'after' => 'tokens'],
		]);
	}

	public function down()
	{
		$this->forge->dropColumn('outbox_templates', 'parent_id');
	}
}
