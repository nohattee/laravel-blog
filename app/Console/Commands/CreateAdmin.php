<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create admin user';

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
        $input['name'] = $this->ask('What is your name?');
        $input['email'] = $this->ask('What is your email?');
        $input['password'] = $this->secret('What is your password?');

        $role = Role::firstOrCreate([
            'name' => 'Admin',
        ], [
            'permissions' => ['*'],
        ]);

        $input['role_id'] = $role->id;

        $validator = Validator::make($input, User::$rules);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 0;
        }

        User::create($input);
        $this->info('Successfully!');
        return 0;
    }
}
