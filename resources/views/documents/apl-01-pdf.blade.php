<!DOCTYPE html>
<html lang="id">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>FR.APL.01 Permohonan Sertifikasi Kompetensi</title>
    <style>
        @page {
            margin: 18mm 16mm 18mm 16mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            color: #111827;
            font-family: "Times New Roman", Times, serif;
            font-size: 12px;
            line-height: 1.35;
        }

        .page {
            page-break-after: always;
        }

        .page:last-child {
            page-break-after: auto;
        }

        .title {
            margin: 0 0 14px;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 0.02em;
            text-transform: uppercase;
        }

        .section-title {
            margin: 16px 0 6px;
            font-size: 15px;
            font-weight: 700;
        }

        .subsection-title {
            margin: 14px 0 8px;
            font-size: 13px;
            font-weight: 700;
        }

        .intro {
            margin: 0 0 8px;
        }

        .field-table,
        .boxed-table,
        .requirements-table,
        .recommendation-table {
            width: 100%;
            border-collapse: collapse;
        }

        .field-table td {
            padding: 4px 2px;
            vertical-align: top;
        }

        .field-label {
            width: 182px;
        }

        .field-separator {
            width: 12px;
            text-align: center;
        }

        .line-value {
            min-height: 16px;
            border-bottom: 1px solid #6b7280;
        }

        .line-value.tight {
            min-height: 14px;
        }

        .muted {
            color: #4b5563;
        }

        .note {
            font-style: italic;
        }

        .boxed-table td,
        .boxed-table th,
        .requirements-table td,
        .requirements-table th,
        .recommendation-table td,
        .recommendation-table th {
            border: 1px solid #374151;
            padding: 6px 7px;
            vertical-align: top;
        }

        .boxed-table th,
        .requirements-table th,
        .recommendation-table th {
            font-weight: 700;
            text-align: center;
        }

        .boxed-table .label-cell {
            width: 150px;
        }

        .checkbox {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 1px solid #111827;
            text-align: center;
            line-height: 12px;
            font-size: 11px;
            vertical-align: middle;
            margin-right: 6px;
        }

        .checkbox.checked {
            font-weight: 700;
        }

        .requirements-table .number-col {
            width: 36px;
            text-align: center;
        }

        .requirements-table .status-col {
            width: 78px;
            text-align: center;
        }

        .signature-box {
            height: 54px;
            text-align: center;
        }

        .signature-box img {
            max-width: 100%;
            max-height: 50px;
        }

        .passport-photo {
            width: 88px;
            height: 112px;
            border: 1px solid #374151;
            text-align: center;
            vertical-align: middle;
        }

        .passport-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .text-center {
            text-align: center;
        }

        .acceptance-line {
            margin: 8px 0;
            font-weight: 700;
        }
    </style>
</head>

<body>
    @php
        $user = $registration->user;
        $scheme = $registration->scheme;
        $genderLabel = match ($user->jenis_kelamin) {
            'L' => 'Laki-laki',
            'P' => 'Wanita',
            default => '-',
        };
    @endphp

    <div class="page">
        <h1 class="title">FR.APL.01. Permohonan Sertifikasi Kompetensi</h1>

        <h2 class="section-title">Bagian 1 : Rincian Data Pemohon Sertifikasi</h2>
        <p class="intro">Pada bagian ini, cantumkan data pribadi, data pendidikan formal serta data pekerjaan anda pada
            saat ini.</p>

        <h3 class="subsection-title">a. Data Pribadi</h3>
        <table class="field-table">
            <tr>
                <td class="field-label">Nama lengkap</td>
                <td class="field-separator">:</td>
                <td>
                    <div class="line-value">{{ $user->nama ?: ' ' }}</div>
                </td>
                <td rowspan="5" style="width: 110px; padding-left: 12px;">
                    <div class="passport-photo">
                        @if ($passportPhotoImage)
                            <img src="{{ $passportPhotoImage }}" alt="Pasfoto">
                        @endif
                    </div>
                </td>
            </tr>
            <tr>
                <td class="field-label">No. KTP/NIK/Paspor</td>
                <td class="field-separator">:</td>
                <td>
                    <div class="line-value">{{ $user->no_ktp ?: $user->nim ?: ' ' }}</div>
                </td>
            </tr>
            <tr>
                <td class="field-label">Tempat / tgl. lahir</td>
                <td class="field-separator">:</td>
                <td>
                    <div class="line-value">
                        {{ trim(($user->tempat_lahir ?: '-') . ' / ' . ($user->tanggal_lahir ? \Carbon\Carbon::parse($user->tanggal_lahir)->translatedFormat('d F Y') : '-')) }}
                    </div>
                </td>
            </tr>
            <tr>
                <td class="field-label">Jenis kelamin</td>
                <td class="field-separator">:</td>
                <td>
                    <div class="line-value">{{ $genderLabel }} / Wanita *)</div>
                </td>
            </tr>
            <tr>
                <td class="field-label">Kebangsaan</td>
                <td class="field-separator">:</td>
                <td>
                    <div class="line-value">{{ $user->umumProfile?->kebangsaan ?: ' ' }}</div>
                </td>
            </tr>
            <tr>
                <td class="field-label">Alamat rumah</td>
                <td class="field-separator">:</td>
                <td colspan="2">
                    <div class="line-value">{{ $user->alamat_rumah ?: ' ' }}</div>
                    <table class="field-table" style="margin-top: 3px;">
                        <tr>
                            <td style="padding-left: 0;">Kode pos</td>
                            <td class="field-separator">:</td>
                            <td style="padding-right: 10px;">
                                <div class="line-value tight">{{ $user->profile?->kode_pos_rumah ?: ' ' }}</div>
                            </td>
                            <td>Rumah</td>
                            <td class="field-separator">:</td>
                            <td style="padding-right: 10px;">
                                <div class="line-value tight">{{ $user->profile?->telp_rumah ?: ' ' }}</div>
                            </td>
                            <td>Kantor</td>
                            <td class="field-separator">:</td>
                            <td>
                                <div class="line-value tight">{{ $user->profile?->telp_kantor ?: ' ' }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-left: 0;">HP</td>
                            <td class="field-separator">:</td>
                            <td style="padding-right: 10px;">
                                <div class="line-value tight">{{ $user->profile?->no_wa ?: ' ' }}</div>
                            </td>
                            <td>E-mail</td>
                            <td class="field-separator">:</td>
                            <td colspan="4">
                                <div class="line-value tight">{{ $user->email ?: ' ' }}</div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="field-label">Kualifikasi Pendidikan</td>
                <td class="field-separator">:</td>
                <td colspan="2">
                    <div class="line-value">
                        {{ $user->kualifikasi_pendidikan ?: ($user->isUpnvjUser() ? 'Mahasiswa' : ' ') }}</div>
                </td>
            </tr>
        </table>

        <p class="note" style="margin: 4px 0 0;">*Coret yang tidak perlu</p>

        <h3 class="subsection-title">b. Data Pekerjaan Sekarang</h3>
        <table class="field-table">
            <tr>
                <td class="field-label">Nama Institusi / Perusahaan</td>
                <td class="field-separator">:</td>
                <td>
                    <div class="line-value">{{ $user->nama_perusahaan ?: ' ' }}</div>
                </td>
            </tr>
            <tr>
                <td class="field-label">Jabatan</td>
                <td class="field-separator">:</td>
                <td>
                    <div class="line-value">{{ $user->jabatan ?: ' ' }}</div>
                </td>
            </tr>
            <tr>
                <td class="field-label">Alamat Kantor</td>
                <td class="field-separator">:</td>
                <td>
                    <div class="line-value">{{ $user->alamat_perusahaan ?: ' ' }}</div>
                    <table class="field-table" style="margin-top: 3px;">
                        <tr>
                            <td style="padding-left: 0;">Kode pos</td>
                            <td class="field-separator">:</td>
                            <td style="padding-right: 10px;">
                                <div class="line-value tight">{{ $user->kode_pos_perusahaan ?: ' ' }}</div>
                            </td>
                            <td>Telp</td>
                            <td class="field-separator">:</td>
                            <td style="padding-right: 10px;">
                                <div class="line-value tight">{{ $user->no_telp_perusahaan ?: ' ' }}</div>
                            </td>
                            <td>Fax</td>
                            <td class="field-separator">:</td>
                            <td>
                                <div class="line-value tight">{{ $user->umumProfile?->fax_perusahaan ?: ' ' }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding-left: 0;">E-mail</td>
                            <td class="field-separator">:</td>
                            <td colspan="7">
                                <div class="line-value tight">{{ $user->email_perusahaan ?: ' ' }}</div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <h2 class="section-title">Bagian 2 : Data Sertifikasi</h2>
        <p class="intro">
            Tuliskan Judul dan Nomor Skema Sertifikasi yang anda ajukan berikut Daftar Unit Kompetensi sesuai kemasan
            pada skema sertifikasi untuk mendapatkan pengakuan sesuai dengan latar belakang pendidikan, pelatihan serta
            pengalaman kerja yang anda miliki.
        </p>

        <table class="boxed-table">
            <tr>
                <td class="label-cell" rowspan="2">Skema Sertifikasi<br>(KKNI/Okupasi/Klaster)</td>
                <td style="width: 60px;">Judul</td>
                <td style="width: 14px; text-align:center;">:</td>
                <td>{{ $scheme?->nama ?: '-' }}</td>
            </tr>
            <tr>
                <td>Nomor</td>
                <td style="text-align:center;">:</td>
                <td>{{ $scheme?->kode_skema ?: '-' }}</td>
            </tr>
            <tr>
                <td rowspan="{{ count($assessmentPurposeOptions) }}">Tujuan Asesmen</td>
                <td colspan="3" style="padding: 0;">
                    <table class="field-table" style="width:100%;">
                        @foreach ($assessmentPurposeOptions as $option)
                            <tr>
                                <td style="border-bottom: 1px solid #374151; padding: 8px 12px;">
                                    <span
                                        class="checkbox {{ $option['checked'] ? 'checked' : '' }}">{{ $option['checked'] ? '✓' : '' }}</span>{{ $option['label'] }}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <div class="page">
        <p style="margin: 0 0 8px; font-weight: 700;">Daftar Unit Kompetensi sesuai kemasan:</p>
        <table class="boxed-table">
            <thead>
                <tr>
                    <th style="width: 42px;">No.</th>
                    <th style="width: 150px;">Kode Unit</th>
                    <th>Judul Unit</th>
                    <th style="width: 120px;">Standar Kompetensi Kerja</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($unitKompetensiRows as $index => $unit)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}.</td>
                        <td>{{ $unit->kode_unit }}</td>
                        <td>{{ $unit->nama_unit }}</td>
                        <td>SKKNI</td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center">1.</td>
                        <td>-</td>
                        <td>-</td>
                        <td>SKKNI</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <h2 class="section-title">Bagian 3 : Bukti Kelengkapan Pemohon</h2>
        <h3 class="subsection-title" style="margin-top: 6px;">3.1 Bukti Persyaratan Dasar Pemohon</h3>

        <table class="requirements-table">
            <thead>
                <tr>
                    <th rowspan="2" class="number-col">No.</th>
                    <th rowspan="2">Bukti Persyaratan Dasar</th>
                    <th colspan="3">Ada</th>
                </tr>
                <tr>
                    <th class="status-col">Memenuhi Syarat</th>
                    <th class="status-col">Tidak Memenuhi Syarat</th>
                    <th class="status-col">Tidak Ada</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($persyaratanDasarRows as $index => $row)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}.</td>
                        <td>{{ $row['label'] }}</td>
                        <td class="text-center">{{ $row['status'] === 'verified' ? '✓' : '' }}</td>
                        <td class="text-center">{{ $row['status'] === 'rejected' ? '✓' : '' }}</td>
                        <td class="text-center">{{ $row['status'] === 'missing' ? '✓' : '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h3 class="subsection-title">3.2 Bukti Administratif</h3>
        <table class="requirements-table">
            <thead>
                <tr>
                    <th rowspan="2" class="number-col">No.</th>
                    <th rowspan="2">Bukti Administratif</th>
                    <th colspan="3">Ada</th>
                </tr>
                <tr>
                    <th class="status-col">Memenuhi Syarat</th>
                    <th class="status-col">Tidak Memenuhi Syarat</th>
                    <th class="status-col">Tidak Ada</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($persyaratanAdministrasiRows as $index => $row)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}.</td>
                        <td>{{ $row['label'] }}</td>
                        <td class="text-center">{{ $row['status'] === 'verified' ? '✓' : '' }}</td>
                        <td class="text-center">{{ $row['status'] === 'rejected' ? '✓' : '' }}</td>
                        <td class="text-center">{{ $row['status'] === 'missing' ? '✓' : '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="page">
        <table class="recommendation-table">
            <tr>
                <td style="width: 58%;">
                    <strong>Rekomendasi (diisi oleh LSP):</strong><br>
                    Berdasarkan ketentuan persyaratan dasar, maka pemohon:
                    <div class="acceptance-line">Diterima / Tidak diterima *) sebagai peserta sertifikasi</div>
                    <span class="note">* coret yang tidak sesuai</span>
                </td>
                <td style="width: 42%; padding: 0;">
                    <table class="field-table" style="width:100%;">
                        <tr>
                            <td colspan="3" style="padding: 8px 10px 2px; font-weight: 700;">Pemohon / Kandidat :
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 108px;">Nama</td>
                            <td class="field-separator">:</td>
                            <td>{{ $user->nama ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Tanda tangan / Tanggal</td>
                            <td class="field-separator">:</td>
                            <td>
                                <div class="signature-box">
                                    @if ($applicantSignatureImage)
                                        <img src="{{ $applicantSignatureImage }}" alt="Tanda tangan pemohon">
                                    @endif
                                </div>
                                <div>{{ $applicantSignedDate ?: '-' }}</div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="height: 130px;">
                    <strong>Catatan :</strong>
                </td>
                <td style="padding: 0;">
                    <table class="field-table" style="width:100%;">
                        <tr>
                            <td colspan="3" style="padding: 8px 10px 2px; font-weight: 700;">Admin LSP :</td>
                        </tr>
                        <tr>
                            <td style="width: 108px;">Nama</td>
                            <td class="field-separator">:</td>
                            <td>{{ $registration->admin_signatory_name ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Tanda tangan / Tanggal</td>
                            <td class="field-separator">:</td>
                            <td>
                                <div class="signature-box">
                                    @if ($adminSignatureImage)
                                        <img src="{{ $adminSignatureImage }}" alt="Tanda tangan admin">
                                    @endif
                                </div>
                                <div>{{ $adminSignedDate ?: '-' }}</div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
