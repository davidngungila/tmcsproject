<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Program;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programs = [
            // Bachelor Degree Programmes
            ['name' => 'Bachelor of Business Information and Communication Technology', 'code' => 'BBICT', 'level' => 'Bachelor', 'duration' => '3 Years', 'delivery_mode' => 'Full-time', 'session' => 'October Intake'],
            ['name' => 'Bachelor of Science in Data Science', 'code' => 'BSDS', 'level' => 'Bachelor', 'duration' => '4 Years', 'delivery_mode' => 'Full-time', 'session' => 'October Intake'],
            ['name' => 'Bachelor of Accounting and Finance', 'code' => 'BAF', 'level' => 'Bachelor', 'duration' => '3 Years', 'delivery_mode' => 'Full-time', 'session' => 'October Intake'],
            ['name' => 'Bachelor of Accounting and Taxation', 'code' => 'BAT', 'level' => 'Bachelor', 'duration' => '3 Years', 'delivery_mode' => 'Full-time', 'session' => 'October Intake'],
            ['name' => 'Bachelor of Human Resource Management', 'code' => 'BHRM', 'level' => 'Bachelor', 'duration' => '3 Years', 'delivery_mode' => 'Full-time', 'session' => 'October Intake'],
            ['name' => 'Bachelor of Banking and Microfinance', 'code' => 'BBMF', 'level' => 'Bachelor', 'duration' => '3 Years', 'delivery_mode' => 'Full-time', 'session' => 'October Intake'],
            ['name' => 'Bachelor of Marketing Management', 'code' => 'BMM', 'level' => 'Bachelor', 'duration' => '3 Years', 'delivery_mode' => 'Full-time', 'session' => 'October Intake'],
            ['name' => 'Bachelor of Procurement and Supply Chain Management', 'code' => 'BPSCM', 'level' => 'Bachelor', 'duration' => '3 Years', 'delivery_mode' => 'Full-time', 'session' => 'October Intake'],
            ['name' => 'Bachelor of Laws', 'code' => 'LL.B', 'level' => 'Bachelor', 'duration' => '3 Years', 'delivery_mode' => 'Full-time', 'session' => 'October Intake'],

            // Diploma Programmes
            ['name' => 'Diploma in Business Information and Communication Technology', 'code' => 'DBICT', 'level' => 'Diploma', 'duration' => '2 Years', 'delivery_mode' => 'Full-time', 'session' => 'October Intake'],
            ['name' => 'Diploma in Microfinance Management', 'code' => 'DMFM', 'level' => 'Diploma', 'duration' => '2 Years', 'delivery_mode' => 'Full-time', 'session' => 'October Intake'],
            ['name' => 'Diploma in Business Enterprise Management', 'code' => 'DBEM', 'level' => 'Diploma', 'duration' => '2 Years', 'delivery_mode' => 'Full-time', 'session' => 'October Intake'],
            ['name' => 'Diploma in Human Resource Management', 'code' => 'DHRM', 'level' => 'Diploma', 'duration' => '2 Years', 'delivery_mode' => 'Full-time', 'session' => 'October Intake'],

            // Certificate Programmes
            ['name' => 'Certificate in Information Technology', 'code' => 'CIT', 'level' => 'Certificate', 'duration' => '1 Year', 'delivery_mode' => 'Full-time', 'session' => 'October Intake'],
            ['name' => 'Certificate in Accounting and Finance', 'code' => 'CAF', 'level' => 'Certificate', 'duration' => '1 Year', 'delivery_mode' => 'Full-time', 'session' => 'October Intake'],
            ['name' => 'Certificate in Microfinance Management', 'code' => 'CMF', 'level' => 'Certificate', 'duration' => '1 Year', 'delivery_mode' => 'Full-time', 'session' => 'October Intake'],
            ['name' => 'Certificate in Law', 'code' => 'CL', 'level' => 'Certificate', 'duration' => '1 Year', 'delivery_mode' => 'Full-time', 'session' => 'October Intake'],
            ['name' => 'Certificate in Human Resource Management', 'code' => 'CHRM', 'level' => 'Certificate', 'duration' => '1 Year', 'delivery_mode' => 'Full-time', 'session' => 'October Intake'],

            // Postgraduate Programmes
            ['name' => 'Doctor of Philosophy', 'code' => 'PhD', 'level' => 'Postgraduate', 'duration' => '3 Years', 'delivery_mode' => 'Full-time', 'session' => 'January – December Intake'],
            ['name' => 'Master of Business Management', 'code' => 'MBM/HD', 'level' => 'Postgraduate', 'duration' => '2 Years', 'delivery_mode' => 'Full-time and Evening', 'session' => 'October Intake'],
            ['name' => 'Master of Human Resource Management', 'code' => 'MHRM/HD', 'level' => 'Postgraduate', 'duration' => '2 Years', 'delivery_mode' => 'Full-time', 'session' => 'October Intake'],
            ['name' => 'Master of Arts in Procurement and Supply Management', 'code' => 'MA-PSM/HD', 'level' => 'Postgraduate', 'duration' => '2 Years', 'delivery_mode' => 'Full-time', 'session' => 'October Intake'],
            ['name' => 'Postgraduate Diploma in Accounting and Finance', 'code' => 'PGD-AF', 'level' => 'Postgraduate', 'duration' => '1 Year', 'delivery_mode' => 'Full-time and Evening', 'session' => 'October Intake'],
        ];

        foreach ($programs as $program) {
            Program::updateOrCreate(['code' => $program['code']], $program);
        }
    }
}
