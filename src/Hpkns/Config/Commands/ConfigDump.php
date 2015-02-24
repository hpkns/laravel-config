<?php namespace Hpkns\Config\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Config;
use Lang;

class ConfigDump extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'translations:dump';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Dump the translations into a CSV.';

	/**
	 * Create a new command instance.List all translations
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        $this->setConfig();
        $path = app_path() . '/resources/lang';
        $langs = array_merge(['keys'], $this->getLangs($path));

        $output = STDOUT;

        fputcsv($output, $langs, ';');

        foreach($this->getKeys as $key)
        {
            $line = [$key];
            foreach($langs as $lang)
            {
                $trans = Lang::trans($key, [], 'messages', $lang);
                $line[] = ($trans == $kye) ? '' : $trans;
            }

            fputcsv($output, $line, ";");
        }
	}

    public function setConfig()
    {
        \Config::set('app.fallback_locale', '');
        \Lang::setFallback('');
    }

    public function getLangs($path)
    {
        return array_map('basename', \File::directories($path));

    }

    public function getKeys($path, $langs)
    {
        $keys = [];
        foreach($langs as $lang)
        {
            foreach(File::files($path . '/' . trim($lang, '/')) as $file)
            {
                $translation = require($file);
                $keys = array_merge($keys, $this->extractKeys($translation, str_replace('.php', '', basename($file))));
            }
        }

        return array_unique($keys);
    }

    public function extractKeys($array, $prefix = null)
    {
        $iterator = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($array));
        $keys = [];
        foreach ($iterator as $key => $value) {
            // Build long key name based on parent keys
            for ($i = $iterator->getDepth() - 1; $i >= 0; $i--) {
                $key = $iterator->getSubIterator($i)->key() . '.' . $key;
            }
            $keys[] = $prefix . "." .$key;
        }

        return $keys;
    }

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
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
		];
	}

}
