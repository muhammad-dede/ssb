<?php

namespace Database\Seeders;

use App\Enums\Status;
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
                'account_number' => '012387654567',
                'account_holder_name' => 'Riwan Febrianto',
            ],
            [
                'bank_code' => '009',
                'account_number' => '076543217654',
                'account_holder_name' => 'Riwan Febrianto',
            ],
        ];
        foreach ($bank_accounts as $key => $value) {
            BankAccount::create($value);
        }

        $assessments = [
            [
                'code' => 'AS1',
                'name' => 'Teknik Dasar',
                'description' => 'Latihan kontrol bola, dribbling, passing, shooting.',
                'percentage' => 40,
                'order' => 1,
            ],
            [
                'code' => 'AS2',
                'name' => 'Taktik Tim',
                'description' => 'Pemahaman formasi, strategi bertahan dan menyerang.',
                'percentage' => 30,
                'order' => 2,
            ],
            [
                'code' => 'AS3',
                'name' => 'Fisik & Mental',
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
                'code' => 'U-13',
                'name' => 'UNDER 13',
                'age_min' => 10,
                'age_max' => 13,
                'description' => 'Tim untuk pemain usia di bawah 13 tahun, sebagai bagian awal akademi sepak bola.',
                'registration_fee' => 1000000,
            ],
            [
                'code' => 'U-15',
                'name' => 'UNDER 15',
                'age_min' => 14,
                'age_max' => 15,
                'description' => 'Tim untuk pemain usia di bawah 15 tahun, sering kali menjadi jenjang pembinaan usia dini.',
                'registration_fee' => 1000000,
            ],
            [
                'code' => 'U-16',
                'name' => 'UNDER 16',
                'age_min' => 15,
                'age_max' => 16,
                'description' => 'Tim untuk pemain usia di bawah 16 tahun, digunakan dalam pengembangan awal talenta muda.',
                'registration_fee' => 1500000,
            ],
            [
                'code' => 'U-17',
                'name' => 'UNDER 17',
                'age_min' => 16,
                'age_max' => 17,
                'description' => 'Tim untuk pemain usia di bawah 17 tahun, seperti Piala Dunia U-17',
                'registration_fee' => 1500000,
            ],
            [
                'code' => 'U-19',
                'name' => 'UNDER 19',
                'age_min' => 18,
                'age_max' => 19,
                'description' => 'Tim untuk pemain usia di bawah 19 tahun, digunakan dalam turnamen usia muda tingkat nasional dan internasional.',
                'registration_fee' => 1500000,
            ],
            [
                'code' => 'U-20',
                'name' => 'UNDER 20',
                'age_min' => 19,
                'age_max' => 20,
                'description' => 'Tim untuk pemain usia di bawah 20 tahun, seperti Piala Dunia U-20.',
                'registration_fee' => 2000000,
            ],
            [
                'code' => 'U-21',
                'name' => 'UNDER 21',
                'age_min' => 20,
                'age_max' => 21,
                'description' => 'Tim untuk pemain usia di bawah 21 tahun. Umumnya digunakan untuk kompetisi pemuda tingkat nasional atau internasional.',
                'registration_fee' => 2000000,
            ],
            [
                'code' => 'U-23',
                'name' => 'UNDER 23',
                'age_min' => 21,
                'age_max' => 23,
                'description' => 'Tim untuk pemain usia di bawah 23 tahun. Biasanya digunakan untuk turnamen seperti SEA Games atau Olimpiade.',
                'registration_fee' => 2000000,
            ],
        ];
        foreach ($programs as $key => $value) {
            Program::create($value);
        }

        $periods = [
            [
                'name' => 'Januari-Juni 2025',
                'start_date' => '2025-01-01',
                'end_date' => '2025-06-30',
                'status' => Status::ACTIVE,
            ],
            [
                'name' => 'Juli-Desember 2025',
                'start_date' => '2025-07-01',
                'end_date' => '2025-12-31',
                'status' => Status::INACTIVE,
            ],
        ];
        foreach ($periods as $key => $value) {
            Period::create($value);
        }
    }
}
