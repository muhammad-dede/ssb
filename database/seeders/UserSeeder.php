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
            ['guard_name' => 'web', 'name' => 'admin.role.index'],
            ['guard_name' => 'web', 'name' => 'admin.role.create'],
            ['guard_name' => 'web', 'name' => 'admin.role.edit'],
            ['guard_name' => 'web', 'name' => 'admin.role.delete'],
            // User
            ['guard_name' => 'web', 'name' => 'admin.user.index'],
            ['guard_name' => 'web', 'name' => 'admin.user.create'],
            ['guard_name' => 'web', 'name' => 'admin.user.edit'],
            ['guard_name' => 'web', 'name' => 'admin.user.delete'],
            // Bank Account
            ['guard_name' => 'web', 'name' => 'admin.bank-account.index'],
            ['guard_name' => 'web', 'name' => 'admin.bank-account.create'],
            ['guard_name' => 'web', 'name' => 'admin.bank-account.edit'],
            ['guard_name' => 'web', 'name' => 'admin.bank-account.delete'],
            // Program
            ['guard_name' => 'web', 'name' => 'admin.program.index'],
            ['guard_name' => 'web', 'name' => 'admin.program.create'],
            ['guard_name' => 'web', 'name' => 'admin.program.edit'],
            ['guard_name' => 'web', 'name' => 'admin.program.delete'],
            // Period
            ['guard_name' => 'web', 'name' => 'admin.period.index'],
            ['guard_name' => 'web', 'name' => 'admin.period.create'],
            ['guard_name' => 'web', 'name' => 'admin.period.edit'],
            ['guard_name' => 'web', 'name' => 'admin.period.delete'],
            // Coach
            ['guard_name' => 'web', 'name' => 'admin.coach.index'],
            ['guard_name' => 'web', 'name' => 'admin.coach.create'],
            ['guard_name' => 'web', 'name' => 'admin.coach.edit'],
            ['guard_name' => 'web', 'name' => 'admin.coach.show'],
            ['guard_name' => 'web', 'name' => 'admin.coach.delete'],
            // Student
            ['guard_name' => 'web', 'name' => 'admin.student.index'],
            ['guard_name' => 'web', 'name' => 'admin.student.create'],
            ['guard_name' => 'web', 'name' => 'admin.student.edit'],
            ['guard_name' => 'web', 'name' => 'admin.student.show'],
            ['guard_name' => 'web', 'name' => 'admin.student.delete'],
            // Registration Student
            ['guard_name' => 'web', 'name' => 'admin.student-program.index'],
            ['guard_name' => 'web', 'name' => 'admin.student-program.create'],
            ['guard_name' => 'web', 'name' => 'admin.student-program.edit'],
            ['guard_name' => 'web', 'name' => 'admin.student-program.show'],
            ['guard_name' => 'web', 'name' => 'admin.student-program.delete'],
            ['guard_name' => 'web', 'name' => 'admin.student-program.payment'],
            // Training
            ['guard_name' => 'web', 'name' => 'admin.training.index'],
            ['guard_name' => 'web', 'name' => 'admin.training.create'],
            ['guard_name' => 'web', 'name' => 'admin.training.edit'],
            ['guard_name' => 'web', 'name' => 'admin.training.show'],
            ['guard_name' => 'web', 'name' => 'admin.training.delete'],
            ['guard_name' => 'web', 'name' => 'admin.training.generate'],
            ['guard_name' => 'web', 'name' => 'admin.training.attendance'],
            ['guard_name' => 'web', 'name' => 'admin.training.assessment'],
            // Macth event
            ['guard_name' => 'web', 'name' => 'admin.match-event.index'],
            ['guard_name' => 'web', 'name' => 'admin.match-event.create'],
            ['guard_name' => 'web', 'name' => 'admin.match-event.edit'],
            ['guard_name' => 'web', 'name' => 'admin.match-event.show'],
            ['guard_name' => 'web', 'name' => 'admin.match-event.delete'],
            ['guard_name' => 'web', 'name' => 'admin.match-event.generate'],
            ['guard_name' => 'web', 'name' => 'admin.match-event.attendance'],
            ['guard_name' => 'web', 'name' => 'admin.match-event.assessment'],
            // Report Student
            ['guard_name' => 'web', 'name' => 'admin.report-student.index'],
            ['guard_name' => 'web', 'name' => 'admin.report-student.show'],
            ['guard_name' => 'web', 'name' => 'admin.report-student.pdf'],
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
                $role->givePermissionTo('admin.role.index');
                $role->givePermissionTo('admin.role.create');
                $role->givePermissionTo('admin.role.edit');
                $role->givePermissionTo('admin.role.delete');
                // User
                $role->givePermissionTo('admin.user.index');
                $role->givePermissionTo('admin.user.create');
                $role->givePermissionTo('admin.user.edit');
                $role->givePermissionTo('admin.user.delete');
                // Bank Account
                $role->givePermissionTo('admin.bank-account.index');
                $role->givePermissionTo('admin.bank-account.create');
                $role->givePermissionTo('admin.bank-account.edit');
                $role->givePermissionTo('admin.bank-account.delete');
                // Program
                $role->givePermissionTo('admin.program.index');
                $role->givePermissionTo('admin.program.create');
                $role->givePermissionTo('admin.program.edit');
                $role->givePermissionTo('admin.program.delete');
                // Period
                $role->givePermissionTo('admin.period.index');
                $role->givePermissionTo('admin.period.create');
                $role->givePermissionTo('admin.period.edit');
                $role->givePermissionTo('admin.period.delete');
                // Coach
                $role->givePermissionTo('admin.coach.index');
                $role->givePermissionTo('admin.coach.create');
                $role->givePermissionTo('admin.coach.edit');
                $role->givePermissionTo('admin.coach.show');
                $role->givePermissionTo('admin.coach.delete');
                // Student
                $role->givePermissionTo('admin.student.index');
                $role->givePermissionTo('admin.student.create');
                $role->givePermissionTo('admin.student.edit');
                $role->givePermissionTo('admin.student.show');
                $role->givePermissionTo('admin.student.delete');
                // Registration
                $role->givePermissionTo('admin.student-program.index');
                $role->givePermissionTo('admin.student-program.create');
                $role->givePermissionTo('admin.student-program.edit');
                $role->givePermissionTo('admin.student-program.show');
                $role->givePermissionTo('admin.student-program.delete');
                $role->givePermissionTo('admin.student-program.payment');
                // Training
                $role->givePermissionTo('admin.training.index');
                $role->givePermissionTo('admin.training.create');
                $role->givePermissionTo('admin.training.edit');
                $role->givePermissionTo('admin.training.show');
                $role->givePermissionTo('admin.training.delete');
                $role->givePermissionTo('admin.training.generate');
                $role->givePermissionTo('admin.training.attendance');
                $role->givePermissionTo('admin.training.assessment');
                // Match Event
                $role->givePermissionTo('admin.match-event.index');
                $role->givePermissionTo('admin.match-event.create');
                $role->givePermissionTo('admin.match-event.edit');
                $role->givePermissionTo('admin.match-event.show');
                $role->givePermissionTo('admin.match-event.delete');
                $role->givePermissionTo('admin.match-event.generate');
                $role->givePermissionTo('admin.match-event.attendance');
                $role->givePermissionTo('admin.match-event.assessment');
                // Report Student
                $role->givePermissionTo('admin.report-student.index');
                $role->givePermissionTo('admin.report-student.show');
                $role->givePermissionTo('admin.report-student.pdf');
            }
            if ($role->name === 'Admin') {
                // Dashboard
                $role->givePermissionTo('dashboard');
                // Coach
                $role->givePermissionTo('admin.coach.index');
                $role->givePermissionTo('admin.coach.create');
                $role->givePermissionTo('admin.coach.edit');
                $role->givePermissionTo('admin.coach.show');
                $role->givePermissionTo('admin.coach.delete');
                // Student
                $role->givePermissionTo('admin.student.index');
                $role->givePermissionTo('admin.student.create');
                $role->givePermissionTo('admin.student.edit');
                $role->givePermissionTo('admin.student.show');
                $role->givePermissionTo('admin.student.delete');
                // Registration
                $role->givePermissionTo('admin.student-program.index');
                $role->givePermissionTo('admin.student-program.create');
                $role->givePermissionTo('admin.student-program.edit');
                $role->givePermissionTo('admin.student-program.show');
                $role->givePermissionTo('admin.student-program.delete');
                $role->givePermissionTo('admin.student-program.payment');
                // Training
                $role->givePermissionTo('admin.training.index');
                $role->givePermissionTo('admin.training.create');
                $role->givePermissionTo('admin.training.edit');
                $role->givePermissionTo('admin.training.show');
                $role->givePermissionTo('admin.training.delete');
                $role->givePermissionTo('admin.training.generate');
                $role->givePermissionTo('admin.training.attendance');
                $role->givePermissionTo('admin.training.assessment');
                // Match Event
                $role->givePermissionTo('admin.match-event.index');
                $role->givePermissionTo('admin.match-event.create');
                $role->givePermissionTo('admin.match-event.edit');
                $role->givePermissionTo('admin.match-event.show');
                $role->givePermissionTo('admin.match-event.delete');
                $role->givePermissionTo('admin.match-event.generate');
                $role->givePermissionTo('admin.match-event.attendance');
                $role->givePermissionTo('admin.match-event.assessment');
                // Report Student
                $role->givePermissionTo('admin.report-student.index');
                $role->givePermissionTo('admin.report-student.show');
                $role->givePermissionTo('admin.report-student.pdf');
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
