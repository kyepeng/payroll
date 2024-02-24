<?php

use App\Timesheet;
use App\User;
use Illuminate\Database\Seeder;

class CreateTimesheetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        foreach($users as $user) {
            Timesheet::create([
                'user_id' => $user->id,
                'date' => now(),
                'hours_worked' => 8,
                'description' => $user->hasRole('admin') ? 'Admin Task' : 'User Task'
            ]);
        }
    }
}
