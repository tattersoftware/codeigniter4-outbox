<?php namespace Tests\Support;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\Mock\MockEmail;

class OutboxTestCase extends CIUnitTestCase
{
	/**
	 * @var boolean
	 */
	protected $refresh = true;

	/**
	 * @var string|array|null
	 */
	protected $namespace = 'Tatter\Outbox';

	/**
	 * Path to a file for attachments.
	 *
	 * @var string
	 */
	protected $file = SUPPORTPATH . 'cat.jpg';

	/**
	 * Email handler
	 *
	 * @var MockEmail
	 */
	protected $email;

	public function setUp(): void
	{
		parent::setUp();

		$this->email = service('email');
	}
}
