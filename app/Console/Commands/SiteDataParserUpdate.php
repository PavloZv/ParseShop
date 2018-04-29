<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SiteDataParserUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'site:update {siteName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update existed data from site';

    /**
     * SiteDataParserUpdate constructor.
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
    public function handle()
    {
        $siteName = str_replace('.', '_', $this->argument('siteName'));
        $config = config("sites.{$siteName}");

        if (!$config) {
            $this->error("Config for {$siteName} does not exist");
            return;
        }

        if (!(isset($config['className']) && class_exists($config['className']))) {
            $this->error("Could not find class!");
            return;
        }

        $parser = app()->make($config['className']);

        try {
            $parser->doParseUpdate($config['countForUpdate']);
        } catch (\Exception $e) {
            echo $e->getMessage(), PHP_EOL;
        }
    }
}
