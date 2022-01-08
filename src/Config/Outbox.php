<?php

namespace Tatter\Outbox\Config;

use CodeIgniter\Config\BaseConfig;

class Outbox extends BaseConfig
{
    /**
     * Whether emails should be logged in the database.
     *
     * @var bool
     */
    public $logging = true;

    /**
     * Whether to include routes to the Templates Controller.
     *
     * @var bool
     */
    public $routeTemplates = false;

    /**
     * Layout to use for Template management.
     *
     * @var string
     *
     * @deprecated Use Tatter\Layouts Config instead
     */
    public $layout = 'Tatter\Outbox\Views\layout';

    /**
     * View path for the default CSS styles to inline, `null` to disable
     *
     * @var string|null
     */
    public $styles = 'Tatter\Outbox\Views\Defaults\styles';
}
