<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TriggersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('triggers')->updateOrInsert(['id' => 1], [
            'id' => 1,
            'title' => 'Issue',
            'code' => 'issue',
        ]);
        \DB::table('triggers')->updateOrInsert(['id' => 2], [
            'id' => 2,
            'title' => 'Merge Request',
            'code' => 'merge_request',
        ]);
        \DB::table('triggers')->updateOrInsert(['id' => 3], [
            'id' => 3,
            'title' => 'Комментарий',
            'code' => 'note',
        ]);
        \DB::table('triggers')->updateOrInsert(['id' => 4], [
            'id' => 4,
            'title' => 'Пайплайн',
            'code' => 'pipeline',
        ]);
    }
}
