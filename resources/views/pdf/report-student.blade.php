<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>RAPORT {{ $student_program->student?->name ?? 'NA' }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .line-space {
            margin: 0;
            line-height: 1.5;
        }

        .header {
            text-align: center;
        }

        .logo {
            float: left;
            width: 80px;
            height: 80px;
        }

        .company-info {
            text-align: center;
        }

        .clear {
            clear: both;
        }

        hr {
            border: 1px solid #000;
            margin: 10px 0;
        }

        .px-12 {
            padding-left: 12px;
            padding-right: 12px;
        }

        .text-center {
            text-align: center;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }

        .table th,
        .table td {
            border: 1px solid #666;
            padding: 5px;
            text-align: left;
            margin: 0;
            line-height: 1.5;
        }

        .w-10 {
            width: 10%;
        }

        .w-16 {
            width: 16%;
        }

        .bg-gray {
            background-color: #f0f0f0;
        }

        .font-semibold {
            font-weight: 600;
        }

        .font-bold {
            font-weight: bold;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{ public_path('logo.webp') }}" class="logo">
        <div class="company-info">
            <h2 class='line-space'>SEKOLAH SEPAK BOLA (SSB)</h2>
            <p class="line-space">Jl. Contoh Alamat No.123, Serang - Indonesia</p>
            <p class="line-space">Email: info@ssbabc.co.id | Telp: 021-12345678</p>
        </div>
        <div class="clear"></div>
        <hr>
    </div>

    <h3 class="text-center line-space">RAPORT SISWA</h3>
    <h3 class="text-center line-space">SEKOLAH SEPAK BOLA</h3>
    <br>

    <div class="px-12">
        <table>
            <tr>
                <td class="line-space"><strong>Nama Lengkap</strong></td>
                <td class="line-space"><strong>&nbsp;:&nbsp;</strong></td>
                <td class="line-space">{{ $student_program->student?->name ?? 'NA' }}</td>
            </tr>
            <tr>
                <td class="line-space">Nomor Identitas</td>
                <td class="line-space"><strong>&nbsp;:&nbsp;</strong></td>
                <td class="line-space">{{ $student_program->student?->national_id_number ?? 'NA' }}</td>
            </tr>
            <tr>
                <td class="line-space">Tempat, Tanggal Lahir</td>
                <td class="line-space"><strong>&nbsp;:&nbsp;</strong></td>
                <td class="line-space">{{ $student_program->student?->place_of_birth ?? 'NA' }},
                    {{ \Carbon\Carbon::parse($student_program->student?->date_of_birth)->translatedFormat('d F Y') ?? 'NA' }}
                </td>
            </tr>
        </table>
    </div>
    <br>
    <table class='table'>
        <thead class='bg-gray'>
            <tr>
                <th>LATIHAN</th>
                <th class="w-16">KODE</th>
                <th class="w-10">NILAI</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($student_program->report['training']['scores'] as $item)
                <tr>
                    <td>{{ $item['name'] ?? 'NA' }}</td>
                    <td>{{ $item['code'] ?? 'NA' }}</td>
                    <td><strong>{{ $item['total_value'] ?? '0' }}</strong></td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2"><strong>TOTAL</strong></td>
                <td><strong>{{ $student_program->report['training']['total_score'] ?? '0' }}</strong></td>
            </tr>
        </tbody>
        <thead class='bg-gray'>
            <tr>
                <th>PERTANDINGAN</th>
                <th class="w-16">KODE</th>
                <th class="w-10">NILAI</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($student_program->report['match_event']['scores'] as $item)
                <tr>
                    <td>{{ $item['name'] ?? 'NA' }}</td>
                    <td>{{ $item['code'] ?? 'NA' }}</td>
                    <td><strong>{{ $item['total_value'] ?? '0' }}</strong></td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2"><strong>TOTAL</strong></td>
                <td><strong>{{ $student_program->report['match_event']['total_score'] ?? '0' }}</strong></td>
            </tr>
            <tr class="bg-gray">
                <td colspan="2"><strong>TOTAL NILAI</strong></td>
                <td><strong>{{ $student_program->report['final_score'] ?? '0' }}</strong></td>
            </tr>
        </tbody>
    </table>

    <br><br><br>
    <table style="width: 100%; margin-top: 40px;">
        <tr>
            <td style="width: 50%; text-align: center;">
                Mengetahui,<br>
                Management SSB
                <br><br><br><br><br>
                <u>_______________________</u>
            </td>
            <td style="width: 50%; text-align: center;">
                Serang, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                Orang Tua / Wali
                <br><br><br><br><br>
                <u>_______________________</u>
            </td>
        </tr>
    </table>
</body>

</html>
