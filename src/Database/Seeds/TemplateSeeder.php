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

		// Prep the parser tokens
		$tokens = ['subject', 'title', 'preview', 'main', 'contact', 'unsubscribe'];
		$data   = [];
		foreach ($tokens as $token)
		{
			$data[$token] = '{' . $token . '}';
		}

		// Render the view into a parsable version and add it to the database
		model(TemplateModel::class)->insert([
			'name'    => 'Default',
			'subject' => '{subject}',
			'body'    => view(config('Outbox')->template, $data, ['debug' => false]),
			'tokens'  => implode(',', $tokens),
		]);
	}
}
