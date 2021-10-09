<?php

namespace Database\Seeders;

use Exception;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->dataFixture();

        $this->call([
            UserSeeder::class,
            PostCategorySeeder::class,
            PostSeeder::class,
        ]);
    }

    private function dataFixture()
    {
        $crud = ['view', 'create', 'update', 'delete'];
        $resources = [
            'post' => $crud,
            'user' => $crud,
            'post-category' => $crud,
            'file' => $crud,
            'role' => $crud,
        ];
        DB::beginTransaction();
        $role = Role::firstOrCreate([
            'name' => 'Admin',
        ]);
        try {
            foreach ($resources as $resource => $permissions) {
                foreach ($permissions as $permission) {
                    $model = Permission::create([
                        'name' => "{$permission}-{$resource}}"
                    ]);

                    $role->permissions()->attach($model->id);
                }
            }
        } catch (Exception $e) {
            DB::rollBack();
        }

        $user = User::firstOrCreate([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => '1qaz@WSX'
        ]);

        $user->roles()->attach($role->id);

        DB::commit();
    }
}
