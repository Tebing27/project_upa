<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Surat Keterangan Kompeten</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            line-height: 1.6;
            color: #111827;
            margin: 36px;
        }

        .title {
            text-align: center;
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .subtitle {
            text-align: center;
            margin-bottom: 28px;
        }

        .signature-wrap {
            margin-top: 48px;
            width: 320px;
            margin-left: auto;
            text-align: center;
        }

        .signature-image,
        .stamp-image {
            max-height: 96px;
            max-width: 140px;
        }

        .signature-assets {
            display: flex;
            align-items: flex-end;
            justify-content: center;
            gap: 12px;
            min-height: 110px;
        }

        table.meta {
            margin-top: 18px;
            margin-bottom: 18px;
            width: 100%;
        }

        table.meta td {
            padding: 2px 0;
            vertical-align: top;
        }
    </style>
</head>
<body>
    <div class="title">SURAT KETERANGAN KOMPETEN</div>
    <div class="subtitle">Nomor: {{ $registration->payment_reference ?? '-' }}</div>

    <p>Yang bertanda tangan di bawah ini menerangkan bahwa:</p>

    <table class="meta">
        <tr>
            <td style="width: 170px;">Nama</td>
            <td>: {{ $registration->user->nama }}</td>
        </tr>
        <tr>
            <td>{{ $registration->user->isGeneralUser() ? 'NIK' : 'NIM' }}</td>
            <td>: {{ $registration->user->isGeneralUser() ? ($registration->user->no_ktp ?: '-') : ($registration->user->nim ?: '-') }}</td>
        </tr>
        <tr>
            <td>Skema Sertifikasi</td>
            <td>: {{ $registration->scheme?->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td>Tanggal Keputusan</td>
            <td>: {{ $registration->updated_at?->translatedFormat('d F Y') ?? '-' }}</td>
        </tr>
    </table>

    <p>
        Berdasarkan hasil asesmen yang telah dilaksanakan, peserta tersebut dinyatakan
        <strong>KOMPETEN</strong> pada skema sertifikasi di atas.
    </p>

    <p>
        Surat keterangan ini diterbitkan sebagai keterangan sementara sampai sertifikat copy resmi
        diunggah oleh admin.
    </p>

    <div class="signature-wrap">
        <p>{{ now()->translatedFormat('d F Y') }}</p>
        <p style="margin-bottom: 12px;">{{ $signatoryName }}</p>

        <div class="signature-assets">
            @if ($stampImage)
                <img src="{{ $stampImage }}" alt="Stempel" class="stamp-image">
            @endif

            @if ($signatureImage)
                <img src="{{ $signatureImage }}" alt="Tanda tangan" class="signature-image">
            @endif
        </div>

        <p style="margin-top: 10px; font-weight: bold;">{{ $signatoryName }}</p>
    </div>
</body>
</html>
