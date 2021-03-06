<?php

namespace DylanLamers\Translate\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Container\Container;
use DylanLamers\Translate\Models\Language;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translate:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display an inspiring quote';

    /**
     * Description
     * @param Container $app
     * @return void
     */
    public function __construct(Container $app)
    {
        $this->app = $app;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $text = 'Thank you for downloading DylanLamers/Translate. You are about to finish the instalation by running this installer. Do you want to proceed?';

        if (! $this->confirm(PHP_EOL.$text.PHP_EOL)) {
            return true;
        }

        $migrations = $this->app->make('migration.repository');
        if (! $migrations->repositoryExists()) {
            $migrations->createRepository();
        }
        
        $migrator = $this->app->make('migrator');
        $migrator->run(__DIR__.'/../../../database/migrations');

        $this->info('Package migrations have been run');

        $this->call('translate:addLanguage');
    }
}
