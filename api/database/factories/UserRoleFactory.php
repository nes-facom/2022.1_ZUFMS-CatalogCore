<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserRoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $usersList = User::all()->pluck('id')->toArray();
        $rolesList = Role::all()->pluck('id')->toArray();

        return [
            'user_id' => $this->faker->randomElement($usersList),
            'role_id' => $this->faker->randomElement($rolesList)
        ];
    }
}
