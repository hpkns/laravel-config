<?php namespace Hpkns\Config\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use League\Csv\Reader;
use Config;
use Lang;
use File;

class ConfigImport extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'translations:import';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import a CSV into config files.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}


    /**
     * Return a CSV reader with the provided document.
     *
     * @return \League\Csv\Reader
     */
    public function getCSV($path)
    {
        $csv = Reader::createFromPath($path);
        $csv->setDelimiter(',');
        $csv->setFlags(\SplFileObject::READ_AHEAD|\SplFileObject::SKIP_EMPTY);;
        return $csv;
    }

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        $csv = $this->getCSV(
            base_path() . '/' . trim($this->option('input'), '/')
        );
        $template = file_get_contents(__DIR__ . '/../../../templates/config.txt');
        $timestamp = date('Y:m:s H-i-s', time());
        $langs = [];

        foreach($csv->fetchAssoc(0) as $i => $trans)
        {
            $key = $trans['key'];

            foreach($trans as $lang => $value)
            {
                if($lang == 'key') continue;

                $lang_key = "{$lang}.{$key}";

                array_set_dot($langs, $lang_key, $value);
            }
        }

        $output = base_path() . '/' . trim($this->option('output'), '/');

        foreach($langs as $lang => $files)
        {
            if( ! file_exists("{$output}/{$lang}"))
            {
                mkdir("{$output}/{$lang}");
            }

            foreach($files as $file => $config)
            {
                $content = str_replace(
                    [':timestamp', ':config'],
                    [$timestamp, pretty_config($config)],
                    $template
                );

                $this->info("Saving translation file {$file}.php to {$output}/{$lang}");

                file_put_contents("{$output}/{$lang}/{$file}.php", $content);
            }
        }
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			// ['example', InputArgument::REQUIRED, 'An example argument.'],
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
            [
                'input', null, InputOption::VALUE_OPTIONAL,
                'Path to the CSV to convert to translation files',
                '/resources/lang/langs.csv'
            ],
            [
                'output', null, InputOption::VALUE_OPTIONAL,
                'Location of the translation files relative to the app root',
                '/resources/lang'
            ],
		];
    }

}
