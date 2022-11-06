<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['id' => Role::IS_ADMIN, 'name' => 'Администратор']);
        Role::create(['id' => Role::IS_HR, 'name' => 'Менеджер по персоналу']);
        Role::create(['id' => Role::IS_MARKETING_MANAGER, 'name' => 'Маркетолог']);
        Role::create(['id' => Role::IS_SALES_MANAGER, 'name' => 'Менеджер по продажам']);
        Role::create(['id' => Role::IS_PURCHASING_MANAGER, 'name' => 'Менеджер по закупкам']);
        Role::create(['id' => Role::IS_ACCOUNTANT, 'name' => 'Бухгалтер']);
    }
}
