<?php

namespace Database\Seeders;

use App\Models\JuridicalStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JuridicalStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        JuridicalStatus::create([
            'id' => JuridicalStatus::IS_ORGANISATION,
            'name' => 'Юридическое лицо'
        ]);
        JuridicalStatus::create([
            'id' => JuridicalStatus::IS_PERSON,
            'name' => 'Физическое лицо'
        ]);
    }
}
