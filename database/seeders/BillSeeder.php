<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Bill;
use Illuminate\Database\Seeder;

class BillSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         if(!Bill::where('name', 'Test Bill')->first()) {
            Bill::create([
                'name' => 'Energia',
                'bill_value' => '100',
                'due_date' => '2028-05-23',
            ]);
            Bill::create([
                'name' => 'Faculdade',
                'bill_value' => '425.50',
                'due_date' => '2028-05-23',
            ]);
        }
    }
}
