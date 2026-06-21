<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class PartyInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'party:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup and initialize a new party-specific project from template';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('==========================================');
        $this->info('  Initializing SIMAP Party Application   ');
        $this->info('==========================================');

        // 1. Copy .env if not exists
        if (!File::exists(base_path('.env'))) {
            $this->comment('Creating .env file from .env.example...');
            File::copy(base_path('.env.example'), base_path('.env'));
            $this->info('.env file successfully created.');
        } else {
            $this->comment('.env file already exists. Skipping copy.');
        }

        // 2. Generate application key
        if (empty(config('app.key'))) {
            $this->comment('Generating application key...');
            Artisan::call('key:generate', ['--force' => true]);
            $this->info(Artisan::output());
        } else {
            $this->comment('Application key already set. Skipping generation.');
        }

        // 3. Migrate and Seed Database
        if ($this->confirm('Do you want to run database migrations now?', true)) {
            $this->comment('Running database migrations...');
            Artisan::call('migrate:fresh', ['--force' => true]);
            $this->info(Artisan::output());

            if ($this->confirm('Do you want to seed basic configuration data (default admin, districts, and party list)?', true)) {
                $this->comment('Seeding database with basic configuration data...');
                Artisan::call('db:seed', ['--force' => true]);
                $this->info(Artisan::output());

                if ($this->confirm('Do you want to seed mock demo data (Dapil, TPS, Caleg, and mock user accounts) for testing/demo purposes?', false)) {
                    $this->comment('Seeding database with mock demo data...');
                    Artisan::call('db:seed', ['--class' => 'PartyDemoSeeder', '--force' => true]);
                    $this->info(Artisan::output());
                }
            }

            $this->info('Database initialization completed.');
        }

        $this->info('==========================================');
        $this->info('  SIMAP Party Project initialized!        ');
        $this->info('==========================================');
        $this->info('Next steps:');
        $this->info('1. Open .env and adjust DB credentials.');
        $this->info('2. Adjust party settings in config/party.php.');
        $this->info('3. Run "npm install && npm run dev" for frontend.');
        $this->info('==========================================');

        return self::SUCCESS;
    }
}
