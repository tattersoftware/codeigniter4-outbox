<?php namespace Tatter\Outbox\Database\Seeds;

use Tatter\Outbox\Models\TemplateModel;

class TemplateSeeder extends \CodeIgniter\Database\Seeder
{
	public function run()
	{
		// Only seed if there are no templates
		if (model(TemplateModel::class)->first())
		{
			return;
		}

		// Prep the parser variables
		$data = [];
		foreach (['title', 'preview', 'main', 'contact', 'unsubscribe'] as $var)
		{
			$data[$var] = '{' . $var . '}';
		}

		// Render the view into a parsable version and add it to the database
		model(TemplateModel::class)->insert([
			'name'    => 'Default',
			'subject' => '{subject}',
			'body'    => view('Tatter\Outbox\Views\layout', $data),
		]);
	}
}
