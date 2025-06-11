<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Dashboard
            ['guard_name' => 'web', 'name' => 'dashboard'],
            // Role
            ['guard_name' => 'web', 'name' => 'role.index'],
            ['guard_name' => 'web', 'name' => 'role.create'],
            ['guard_name' => 'web', 'name' => 'role.edit'],
            ['guard_name' => 'web', 'name' => 'role.show'],
            ['guard_name' => 'web', 'name' => 'role.delete'],
            // User
            ['guard_name' => 'web', 'name' => 'user.index'],
            ['guard_name' => 'web', 'name' => 'user.create'],
            ['guard_name' => 'web', 'name' => 'user.edit'],
            ['guard_name' => 'web', 'name' => 'user.show'],
            ['guard_name' => 'web', 'name' => 'user.delete'],
            // Bank Account
            ['guard_name' => 'web', 'name' => 'bank-account.index'],
            ['guard_name' => 'web', 'name' => 'bank-account.create'],
            ['guard_name' => 'web', 'name' => 'bank-account.edit'],
            ['guard_name' => 'web', 'name' => 'bank-account.show'],
            ['guard_name' => 'web', 'name' => 'bank-account.delete'],
            // Program
            ['guard_name' => 'web', 'name' => 'program.index'],
            ['guard_name' => 'web', 'name' => 'program.create'],
            ['guard_name' => 'web', 'name' => 'program.edit'],
            ['guard_name' => 'web', 'name' => 'program.show'],
            ['guard_name' => 'web', 'name' => 'program.delete'],
            // Period
            ['guard_name' => 'web', 'name' => 'period.index'],
            ['guard_name' => 'web', 'name' => 'period.create'],
            ['guard_name' => 'web', 'name' => 'period.edit'],
            ['guard_name' => 'web', 'name' => 'period.show'],
            ['guard_name' => 'web', 'name' => 'period.delete'],
            // Coach
            ['guard_name' => 'web', 'name' => 'coach.index'],
            ['guard_name' => 'web', 'name' => 'coach.create'],
            ['guard_name' => 'web', 'name' => 'coach.edit'],
            ['guard_name' => 'web', 'name' => 'coach.show'],
            ['guard_name' => 'web', 'name' => 'coach.delete'],
            // Student
            ['guard_name' => 'web', 'name' => 'student.index'],
            ['guard_name' => 'web', 'name' => 'student.create'],
            ['guard_name' => 'web', 'name' => 'student.edit'],
            ['guard_name' => 'web', 'name' => 'student.show'],
            ['guard_name' => 'web', 'name' => 'student.delete'],
            // Registration Student
            ['guard_name' => 'web', 'name' => 'student-program.index'],
            ['guard_name' => 'web', 'name' => 'student-program.create'],
            ['guard_name' => 'web', 'name' => 'student-program.edit'],
            ['guard_name' => 'web', 'name' => 'student-program.show'],
            ['guard_name' => 'web', 'name' => 'student-program.delete'],
            // Training
            ['guard_name' => 'web', 'name' => 'training.index'],
            ['guard_name' => 'web', 'name' => 'training.create'],
            ['guard_name' => 'web', 'name' => 'training.edit'],
            ['guard_name' => 'web', 'name' => 'training.show'],
            ['guard_name' => 'web', 'name' => 'training.delete'],
            // Macth event
            ['guard_name' => 'web', 'name' => 'match-event.index'],
            ['guard_name' => 'web', 'name' => 'match-event.create'],
            ['guard_name' => 'web', 'name' => 'match-event.edit'],
            ['guard_name' => 'web', 'name' => 'match-event.show'],
            ['guard_name' => 'web', 'name' => 'match-event.delete'],
            // Report Student
            ['guard_name' => 'web', 'name' => 'report-student.index'],
            ['guard_name' => 'web', 'name' => 'report-student.create'],
            ['guard_name' => 'web', 'name' => 'report-student.edit'],
            ['guard_name' => 'web', 'name' => 'report-student.show'],
            ['guard_name' => 'web', 'name' => 'report-student.delete'],
        ];

        foreach ($permissions as $key => $value) {
            Permission::create($value);
        }

        $roles = [
            ['guard_name' => 'web', 'name' => 'Super Admin'],
            ['guard_name' => 'web', 'name' => 'Admin'],
            ['guard_name' => 'web', 'name' => 'Coach'],
            ['guard_name' => 'web', 'name' => 'Student'],
            ['guard_name' => 'web', 'name' => 'Leader'],
        ];

        foreach ($roles as $key => $value) {
            $role =  Role::create($value);

            if ($role->name === 'Super Admin') {
                // Dashboard
                $role->givePermissionTo('dashboard');
                // Role
                $role->givePermissionTo('role.index');
                $role->givePermissionTo('role.create');
                $role->givePermissionTo('role.edit');
                $role->givePermissionTo('role.show');
                $role->givePermissionTo('role.delete');
                // User
                $role->givePermissionTo('user.index');
                $role->givePermissionTo('user.create');
                $role->givePermissionTo('user.edit');
                $role->givePermissionTo('user.show');
                $role->givePermissionTo('user.delete');
                // Bank Account
                $role->givePermissionTo('bank-account.index');
                $role->givePermissionTo('bank-account.create');
                $role->givePermissionTo('bank-account.edit');
                $role->givePermissionTo('bank-account.show');
                $role->givePermissionTo('bank-account.delete');
                // Program
                $role->givePermissionTo('program.index');
                $role->givePermissionTo('program.create');
                $role->givePermissionTo('program.edit');
                $role->givePermissionTo('program.show');
                $role->givePermissionTo('program.delete');
                // Period
                $role->givePermissionTo('period.index');
                $role->givePermissionTo('period.create');
                $role->givePermissionTo('period.edit');
                $role->givePermissionTo('period.show');
                $role->givePermissionTo('period.delete');
                // Coach
                $role->givePermissionTo('coach.index');
                $role->givePermissionTo('coach.create');
                $role->givePermissionTo('coach.edit');
                $role->givePermissionTo('coach.show');
                $role->givePermissionTo('coach.delete');
                // Student
                $role->givePermissionTo('student.index');
                $role->givePermissionTo('student.create');
                $role->givePermissionTo('student.edit');
                $role->givePermissionTo('student.show');
                $role->givePermissionTo('student.delete');
                // Registration
                $role->givePermissionTo('student-program.index');
                $role->givePermissionTo('student-program.create');
                $role->givePermissionTo('student-program.edit');
                $role->givePermissionTo('student-program.show');
                $role->givePermissionTo('student-program.delete');
                // Training
                $role->givePermissionTo('training.index');
                $role->givePermissionTo('training.create');
                $role->givePermissionTo('training.edit');
                $role->givePermissionTo('training.show');
                $role->givePermissionTo('training.delete');
                // Match Event
                $role->givePermissionTo('match-event.index');
                $role->givePermissionTo('match-event.create');
                $role->givePermissionTo('match-event.edit');
                $role->givePermissionTo('match-event.show');
                $role->givePermissionTo('match-event.delete');
                // Report Student
                $role->givePermissionTo('report-student.index');
                $role->givePermissionTo('report-student.create');
                $role->givePermissionTo('report-student.edit');
                $role->givePermissionTo('report-student.show');
                $role->givePermissionTo('report-student.delete');
            }
            if ($role->name === 'Admin') {
                // Dashboard
                $role->givePermissionTo('dashboard');
                // Coach
                $role->givePermissionTo('coach.index');
                $role->givePermissionTo('coach.create');
                $role->givePermissionTo('coach.edit');
                $role->givePermissionTo('coach.show');
                $role->givePermissionTo('coach.delete');
                // Student
                $role->givePermissionTo('student.index');
                $role->givePermissionTo('student.create');
                $role->givePermissionTo('student.edit');
                $role->givePermissionTo('student.show');
                $role->givePermissionTo('student.delete');
                // Registration
                $role->givePermissionTo('student-program.index');
                $role->givePermissionTo('student-program.create');
                $role->givePermissionTo('student-program.edit');
                $role->givePermissionTo('student-program.show');
                $role->givePermissionTo('student-program.delete');
                // Training
                $role->givePermissionTo('training.index');
                $role->givePermissionTo('training.create');
                $role->givePermissionTo('training.edit');
                $role->givePermissionTo('training.show');
                $role->givePermissionTo('training.delete');
                // Match Event
                $role->givePermissionTo('match-event.index');
                $role->givePermissionTo('match-event.create');
                $role->givePermissionTo('match-event.edit');
                $role->givePermissionTo('match-event.show');
                $role->givePermissionTo('match-event.delete');
                // Report Student
                $role->givePermissionTo('report-student.index');
                $role->givePermissionTo('report-student.create');
                $role->givePermissionTo('report-student.edit');
                $role->givePermissionTo('report-student.show');
                $role->givePermissionTo('report-student.delete');
            }
            if ($role->name === 'Leader') {
                // Dashboard
                $role->givePermissionTo('dashboard');
            }
            if ($role->name === 'Student') {
                // Dashboard
                $role->givePermissionTo('dashboard');
            }
            if ($role->name === 'Coach') {
                // Dashboard
                $role->givePermissionTo('dashboard');
            }
        }

        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'super.admin@ssb.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Admin SSB',
                'email' => 'admin@ssb.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Pimpinan SSB',
                'email' => 'leader@ssb.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
            ],
        ];

        foreach ($users as $key => $value) {
            $user = User::create($value);
            if ($user->name === 'Super Admin') {
                $user->assignRole('Super Admin');
            }
            if ($user->name === 'Admin SSB') {
                $user->assignRole('Admin');
            }
            if ($user->name === 'Pimpinan SSB') {
                $user->assignRole('Leader');
            }
        }
    }
}
