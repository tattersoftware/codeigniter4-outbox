<?php

namespace Tatter\Outbox\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Tatter\Outbox\Models\TemplateModel;

class TemplateSeeder extends Seeder
{
    public function run()
    {
        // Only seed if there are no templates
        if (model(TemplateModel::class)->first()) {
            return;
        }

        // Add the Default Template to the database
        model(TemplateModel::class)->insert([
            'name'    => 'Default',
            'subject' => '{subject}',
            'body'    => view('Tatter\Outbox\Views\Defaults\template'),
        ]);
    }
}
