<?php

namespace Ferdiunal\NovaShield\Commands;

use Ferdiunal\NovaShield\Lib\SuperAdmin;
use Illuminate\Database\Console\Migrations\BaseCommand;

use function Laravel\Prompts\multisearch;
use function Laravel\Prompts\spin;

class MakeSuperAdminCommand extends BaseCommand
{
    protected $signature = 'nova-shield:super-admin {--user= : ID of user to make super admin}';

    protected $description = 'Make a user super admin';

    protected $users = [];

    public function clear()
    {
        if (windows_os()) {
            system('cls');
        } else {
            system('clear');
        }
    }

    public function handle()
    {
        $userId = $this->option('user');

        if ($userId) {
            $this->createSuperAdminRole();

            $user = SuperAdmin::userModel()::find($userId);
            if (! $user) {
                $this->error('User not found');

                return;
            }

            [$name] = SuperAdmin::defaults();

            $user->assignRole($name);
            $this->info("User {$user->name} is now super admin 💪");

            return;
        }

        $this->users = spin(
            fn () => $this->getUsers(),
            'Fetching users...'
        );

        $this->clear();

        $choices = $this->getChoicesUsers();

        $this->clear();

        if (empty($choices)) {
            $this->error("No user selected\nBye! 👋");

            return;
        }

        spin(
            fn () => $this->syncMakeSuperAdmin($choices),
            'Making user(s) super admin...'
        );
    }

    public function syncMakeSuperAdmin(array $choices)
    {
        $this->clear();

        $role = $this->createSuperAdminRole();

        $role->users()->syncWithoutDetaching($choices);

        $this->info("User(s) are now super admin 💪\nBye! 👋");
    }

    public function createSuperAdminRole()
    {
        $this->call('nova-shield:sync-super-admin');

        return (new SuperAdmin)->getSuperAdminRole();
    }

    public function getChoicesUsers()
    {
        return multisearch(
            label: 'Select user(s) to make super admin',
            options: fn ($search) => array_values(array_filter(
                $this->users,
                fn ($choice) => str_contains(strtolower($choice), strtolower($search))
            )),
            placeholder: 'Search email address',
            hint: 'Press <space> to select, <return> to submit',
            scroll: 15,
            transform: fn ($choices) => array_map(
                fn ($choice) => str($choice)->before(' <')->value,
                $choices
            )
        );
    }

    public function getUsers()
    {
        return SuperAdmin::userModel()::query()
            ->orderBy('created_at')
            ->whereDoesntHave(
                'roles',
                fn ($query) => $query->where('name', 'super-admin')
            )
            ->pluck('email', 'id')
            ->map(
                fn ($email, $id) => "$id <$email>"
            )
            ->values()
            ->toArray();
    }
}
