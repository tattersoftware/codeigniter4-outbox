<?php namespace Tatter\Outbox\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOutboxTables extends Migration
{
	public function up()
	{
		// Emails
		$fields = [
			'subject'       => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
			'body'          => ['type' => 'text', 'null' => true],
			'fromEmail'     => ['type' => 'varchar', 'constraint' => 63, 'null' => true],
			'fromName'      => ['type' => 'varchar', 'constraint' => 63, 'null' => true],
			'userAgent'     => ['type' => 'varchar', 'constraint' => 63, 'null' => true],
			'protocol'      => ['type' => 'varchar', 'constraint' => 63, 'null' => true],
			'mailType'      => ['type' => 'varchar', 'constraint' => 63, 'null' => true],
			'charset'       => ['type' => 'varchar', 'constraint' => 63, 'null' => true],
			'priority'      => ['type' => 'int', 'null' => true],
			'sendMultipart' => ['type' => 'boolean', 'default' => 1],
			'BCCBatchMode'  => ['type' => 'boolean', 'default' => 1],
			'BCCBatchSize'  => ['type' => 'int', 'unsigned' => true, 'null' => true],
			'created_at'    => ['type' => 'datetime', 'null' => true],
			'updated_at'    => ['type' => 'datetime', 'null' => true],
		];
		
		$this->forge->addField('id');
		$this->forge->addField($fields);

		$this->forge->addKey('subject');
		$this->forge->addKey('created_at');
		
		$this->forge->createTable('outbox_emails');

		// Recipients
		$fields = [
			'outbox_email_id' => ['type' => 'int'],
			'email'           => ['type' => 'varchar', 'constraint' => 255],
		];
		
		$this->forge->addField('id');
		$this->forge->addField($fields);

		$this->forge->addKey('outbox_email_id');
		$this->forge->addKey('email');
		
		$this->forge->createTable('outbox_recipients');

		// Attachments
		$fields = [
			'outbox_email_id' => ['type' => 'int'],
			'name'          => ['type' => 'varchar', 'constraint' => 255],
			'newName'       => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
			'disposition'   => ['type' => 'varchar', 'constraint' => 63, 'null' => true],
			'type'          => ['type' => 'varchar', 'constraint' => 63, 'null' => true],
			'multipart'     => ['type' => 'varchar', 'constraint' => 63, 'null' => true],
			'bytes'         => ['type' => 'int', 'unsigned' => true],
		];
		
		$this->forge->addField('id');
		$this->forge->addField($fields);

		$this->forge->addKey('outbox_email_id');
		$this->forge->addKey('name');
		$this->forge->addKey('bytes');
		
		$this->forge->createTable('outbox_attachments');
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$this->forge->dropTable('outbox_emails');
		$this->forge->dropTable('outbox_recipients');
		$this->forge->dropTable('outbox_attachments');
	}
}
