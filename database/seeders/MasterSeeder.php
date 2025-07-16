<?php

namespace Database\Seeders;

use App\Enums\StatusPeriod;
use App\Models\Assessment;
use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\Period;
use App\Models\Program;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banks = [
            ['code' => '002', 'name' => 'Bank Rakyat Indonesia (BRI)'],
            ['code' => '008', 'name' => 'Bank Mandiri'],
            ['code' => '009', 'name' => 'Bank Negara Indonesia (BNI)'],
            ['code' => '014', 'name' => 'Bank Central Asia (BCA)'],
            ['code' => '011', 'name' => 'Bank Danamon'],
            ['code' => '013', 'name' => 'Permata Bank'],
            ['code' => '016', 'name' => 'Bank Maybank Indonesia'],
            ['code' => '022', 'name' => 'CIMB Niaga'],
            ['code' => '028', 'name' => 'Citibank'],
            ['code' => '031', 'name' => 'Bank HSBC Indonesia'],
            ['code' => '036', 'name' => 'Bank BTPN'],
            ['code' => '037', 'name' => 'Bank Artha Graha Internasional'],
            ['code' => '042', 'name' => 'Bank Muamalat Indonesia'],
            ['code' => '046', 'name' => 'Bank DBS Indonesia'],
            ['code' => '050', 'name' => 'Standard Chartered Bank'],
            ['code' => '052', 'name' => 'Bank Panin'],
            ['code' => '053', 'name' => 'Bank Woori Saudara Indonesia 1906'],
            ['code' => '054', 'name' => 'Bank Bukopin'],
            ['code' => '057', 'name' => 'Bank Bumi Arta'],
            ['code' => '059', 'name' => 'Bank Mayapada Internasional'],
            ['code' => '110', 'name' => 'Bank Jabar Banten (BJB)'],
            ['code' => '111', 'name' => 'Bank DKI'],
            ['code' => '112', 'name' => 'Bank DIY'],
            ['code' => '113', 'name' => 'Bank Jateng'],
            ['code' => '114', 'name' => 'Bank Jatim'],
            ['code' => '115', 'name' => 'Bank Jambi'],
            ['code' => '116', 'name' => 'Bank Aceh'],
            ['code' => '117', 'name' => 'Bank Sumut'],
            ['code' => '118', 'name' => 'Bank Nagari'],
            ['code' => '119', 'name' => 'Bank Riau Kepri'],
            ['code' => '120', 'name' => 'Bank Sumsel Babel'],
            ['code' => '121', 'name' => 'Bank Lampung'],
            ['code' => '122', 'name' => 'Bank Kalsel'],
            ['code' => '123', 'name' => 'Bank Kalbar'],
            ['code' => '124', 'name' => 'Bank Kaltimtara'],
            ['code' => '125', 'name' => 'Bank Kalteng'],
            ['code' => '126', 'name' => 'Bank Sulselbar'],
            ['code' => '127', 'name' => 'Bank SulutGo'],
            ['code' => '128', 'name' => 'Bank NTB Syariah'],
            ['code' => '129', 'name' => 'Bank NTT'],
            ['code' => '130', 'name' => 'Bank Maluku Malut'],
            ['code' => '131', 'name' => 'Bank Papua'],
            ['code' => '132', 'name' => 'Bank Bengkulu'],
            ['code' => '133', 'name' => 'Bank Sulteng'],
            ['code' => '134', 'name' => 'Bank Sultra'],
            ['code' => '135', 'name' => 'Bank Banten'],
        ];
        foreach ($banks as $key => $value) {
            Bank::create($value);
        }

        $bank_accounts = [
            [
                'bank_code' => '014',
                'account_number' => '2452854601',
                'account_holder_name' => 'FATHULLOH AL HASAN',
            ],
            [
                'bank_code' => '008',
                'account_number' => '1630011812073',
                'account_holder_name' => 'FATHULLOH AL HASAN',
            ],
        ];
        foreach ($bank_accounts as $key => $value) {
            BankAccount::create($value);
        }

        $assessments = [
            [
                'code' => 'AS1',
                'name' => 'TEKNIK DASAR',
                'description' => 'Latihan kontrol bola, dribbling, passing, shooting.',
                'percentage' => 40,
                'order' => 1,
            ],
            [
                'code' => 'AS2',
                'name' => 'TAKTIK TIM',
                'description' => 'Pemahaman formasi, strategi bertahan dan menyerang.',
                'percentage' => 30,
                'order' => 2,
            ],
            [
                'code' => 'AS3',
                'name' => 'FISIK & MENTAL',
                'description' => 'Latihan fisik, kecepatan, dan membangun karakter atlet.',
                'percentage' => 30,
                'order' => 3,
            ],
        ];
        foreach ($assessments as $key => $value) {
            Assessment::create($value);
        }

        $programs = [
            [
                'code' => 'U-12',
                'name' => 'UNDER 12',
                'age_min' => 8,
                'age_max' => 12,
                'description' => 'Tim untuk pemain usia di bawah 12 tahun, sebagai bagian awal akademi sepak bola.',
                'registration_fee' => 1000000,
            ],
            [
                'code' => 'U-18',
                'name' => 'UNDER 18',
                'age_min' => 13,
                'age_max' => 18,
                'description' => 'Tim untuk pemain usia di bawah 18 tahun.',
                'registration_fee' => 1500000,
            ],
        ];
        foreach ($programs as $key => $value) {
            Program::create($value);
        }

        $periods = [
            [
                'name' => 'JANUARI-JUNI 2025',
                'start_date' => '2025-01-01',
                'end_date' => '2025-06-30',
                'status' => StatusPeriod::ACTIVE,
            ],
            [
                'name' => 'JULI-DESEMBER 2025',
                'start_date' => '2025-07-01',
                'end_date' => '2025-12-31',
                'status' => StatusPeriod::INACTIVE,
            ],
        ];
        foreach ($periods as $key => $value) {
            Period::create($value);
        }
    }
}
