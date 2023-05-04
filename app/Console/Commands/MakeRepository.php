<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeRepository extends Command
{
    protected $signature = 'make:repository {name}';

    protected $description = 'Create a new repository class';

    public function handle()
    {
        $name = $this->argument('name');

        $className = $name . 'Repository';

        $path = app_path('Repositories/' . $className . '.php');

        if (file_exists($path)) {
            $this->error($className . ' already exists!');
            return;
        }

        $stub = file_get_contents(__DIR__ . '/stubs/repository.stub');

        $stub = str_replace('{{ className }}', $className, $stub);

        file_put_contents($path, $stub);

        $this->info($className . ' created successfully.');
    }
}
