<?php

namespace Ferdiunal\NovaShield\Commands;

use Ferdiunal\NovaShield\Lib\SuperAdmin;
use Illuminate\Database\Console\Migrations\BaseCommand;

use function Laravel\Prompts\spin;

class SuperAdminSyncPermissions extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nova-shield:sync-super-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync permissions to Super Admin role';

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
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        spin(
            fn () => $this->sync(),
            'Syncing super admin role with all permissions...'
        );

        $this->info('Super admin role synced with all permissions 🚀');
    }

    public function sync()
    {
        (new SuperAdmin)->syncRoleAndPermissions();
    }
}
