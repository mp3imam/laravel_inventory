<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FAQSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('faq')->insert([
            [
                'question' => 'pertanyaan1',
                'answer'   => 'jawaban1|jawaban1'
            ],
            [
                'question' => 'pertanyaan2',
                'answer'   => 'jawaban2'
            ],
            [
                'question' => 'pertanyaan3',
                'answer'   => 'jawaban3'
            ],
            [
                'question' => 'pertanyaan4',
                'answer'   => 'jawaban4'
            ],
            [
                'question' => 'pertanyaan5',
                'answer'   => 'jawaban5'
            ],
            [
                'question' => 'pertanyaan6',
                'answer'   => 'jawaban6'
            ],
            [
                'question' => 'pertanyaan7',
                'answer'   => 'jawaban7'
            ],
            [
                'question' => 'pertanyaan8',
                'answer'   => 'jawaban8'
            ],
            [
                'question' => 'pertanyaan9',
                'answer'   => 'jawaban9'
            ],
            [
                'question' => 'pertanyaan10',
                'answer'   => 'jawaban10'
            ],
        ]);
    }
}