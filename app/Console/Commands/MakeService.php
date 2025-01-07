<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $servicePath = app_path("Services/{$name}.php");

        if (File::exists($servicePath)) {
            $this->error("Service {$name} already exists!");
            return;
        }

        File::ensureDirectoryExists(app_path('Services'));

        $template = <<<EOT
        <?php

        namespace App\Services;

        class {$name}
        {
            //
        }
        EOT;

        File::put($servicePath, $template);
        $this->info("Service {$name} created successfully.");
    }
}
