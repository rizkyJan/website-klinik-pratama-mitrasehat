<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;
use App\Models\DoctorSchedule;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        // Dokter Umum
        $umum = [
            ['name' => 'dr. Ade Triana Hapsari', 'photo' => null],
            ['name' => 'dr. Evika Agustina', 'photo' => null],
            ['name' => 'dr. Asri Probowati Ayuningrum', 'photo' => null],
            ['name' => 'dr. Virgi Parisa', 'photo' => null],
        ];

        foreach ($umum as $i => $doc) {
            $doctor = Doctor::updateOrCreate(
                ['name' => $doc['name']],
                [
                    'name' => $doc['name'],
                    'specialization' => 'Dokter Umum',
                    'photo' => $doc['photo'],
                    'sort_order' => $i + 1,
                    'is_active' => true,
                ]
            );

            $schedules = $this->getScheduleForDoctor($i);
            foreach ($schedules as $sched) {
                DoctorSchedule::updateOrCreate(
                                    ['doctor_id' => $doctor->id, 'day' => $sched['day']],
                                    array_merge(['doctor_id' => $doctor->id], $sched)
                                );
            }
        }

        // Dokter Gigi
        $gigi = [
            ['name' => 'drg. Rina Wahyu Utami', 'photo' => null],
            ['name' => 'drg. Anna Permadani', 'photo' => null],
        ];

        foreach ($gigi as $i => $doc) {
            $doctor = Doctor::updateOrCreate(
                ['name' => $doc['name']],
                [
                    'name' => $doc['name'],
                    'specialization' => 'Dokter Gigi',
                    'photo' => $doc['photo'],
                    'sort_order' => $i + 1,
                    'is_active' => true,
                ]
            );
        }
    }

    private function getScheduleForDoctor(int $index): array
    {
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        $schedules = [
            // dr. Ade Triana Hapsari
            0 => ['14.00-21.00', 'LIBUR', '07.00-13.00', 'LIBUR', '07.00-13.00', '07.00-13.00', 'LIBUR'],
            // dr. Evika Agustina
            1 => ['LIBUR', '14.00-21.00', 'LIBUR', '14.00-21.00', 'LIBUR', 'LIBUR', '07.00-13.00'],
            // dr. Asri Probowati
            2 => ['LIBUR', '14.00-21.00', 'LIBUR', '14.00-21.00', 'LIBUR', 'LIBUR', '07.00-13.00'],
            // dr. Virgi Parisa
            3 => ['07.00-13.00', '10.00-12.00', '14.00-21.00', 'LIBUR', 'LIBUR', '14.00-21.00', 'LIBUR'],
        ];

        $result = [];
        $pattern = $schedules[$index] ?? [];
        foreach ($days as $i => $day) {
            $val = $pattern[$i] ?? 'LIBUR';
            if ($val === 'LIBUR') {
                $result[] = ['day' => $day, 'start_time' => null, 'end_time' => null, 'is_off' => true];
            } else {
                $parts = explode('-', $val);
                $result[] = ['day' => $day, 'start_time' => $parts[0], 'end_time' => $parts[1] ?? null, 'is_off' => false];
            }
        }

        return $result;
    }
}