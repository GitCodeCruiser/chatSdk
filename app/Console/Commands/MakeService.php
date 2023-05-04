<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeService extends Command
{
    protected $signature = 'make:service {name}';

    protected $description = 'Create a new service class';

    public function handle()
    {
        $name = $this->argument('name');

        $className = $name . 'Service';

        $path = app_path('Services/' . $className . '.php');

        if (file_exists($path)) {
            $this->error($className . ' already exists!');
            return;
        }

        $stub = file_get_contents(__DIR__ . '/stubs/service.stub');

        $stub = str_replace('{{ className }}', $className, $stub);

        file_put_contents($path, $stub);

        $this->info($className . ' created successfully.');
    }
}
