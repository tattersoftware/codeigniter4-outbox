<?php

namespace Tatter\Outbox\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Publisher\Publisher;
use Throwable;

class OutboxPublish extends BaseCommand
{
    protected $group       = 'Outbox';
    protected $name        = 'outbox:publish';
    protected $description = 'Publish Outbox config file into the current application.';

    public function run(array $params): void
    {
        $source = service('autoloader')->getNamespace('Tatter\\Outbox')[0];

        $publisher = new Publisher($source, APPPATH);

        try {
            $publisher->addPaths([
                'Config/Outbox.php',
            ])->merge(false);
        } catch (Throwable $e) {
            $this->showError($e);

            return;
        }

        foreach ($publisher->getPublished() as $file) {
            $contents = file_get_contents($file);
            $contents = str_replace('namespace Tatter\\Outbox\\Config', 'namespace Config', $contents);
            $contents = str_replace('use CodeIgniter\\Config\\BaseConfig', 'use Tatter\\Outbox\\Config\\Outbox as BaseOutbox', $contents);
            $contents = str_replace('class Outbox extends BaseConfig', 'class Outbox extends BaseOutbox', $contents);
            file_put_contents($file, $contents);
        }

        CLI::write(CLI::color('  Published! ', 'green') . 'You can customize the configuration by editing the "app/Config/Outbox.php" file.');
    }
}
