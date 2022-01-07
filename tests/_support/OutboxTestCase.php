<?php

namespace Tests\Support;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\Mock\MockEmail;

/**
 * @internal
 */
abstract class OutboxTestCase extends CIUnitTestCase
{
    /**
     * @var bool
     */
    protected $refresh = true;

    /**
     * @var array|string|null
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

    protected function setUp(): void
    {
        parent::setUp();

        $this->email = service('email');
    }
}
