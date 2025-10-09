<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        @page {
            margin: 0 15px;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
        }
        .covers {
            min-height: 100vh;
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
        }
        .report-title {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        .report-number {
            font-size: 18px;
            text-align: center;
            margin: 10px 0 30px;
        }
        .ship-details {
            width: 80%;
            margin: 20px auto;
            font-size: 18px;
            line-height: 1;
        }
        .ship-details td {
            padding: 5px;
        }
        .ship-details td:first-child {
            padding-left: 10px;
            text-align: left;
            width: 30%;
        }
        .ship-details td:nth-child(2) {
            width: 5%;
            text-align: center;
        }
        .ship-details td:last-child {
            text-align: left;
            width: 65%;
        }
        .logo-container {
            text-align: center;
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 250px 50px;
        }
        .logo-container img {
            max-width: 200px;
            height: auto;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin: 0 0 20px 0;
            padding: 0;
        }
    </style>
</head>
<body>
    <div class="covers">
        <h2 class="report-title"><u>ULTRASONIC THICKNESS MEASUREMENT REPORT</u></h2>
        <table class="ship-details">
            <tr>
                <td>Report No.</td>
                <td>:</td>
                <td><?= $report->report_number ?? '-' ?></td>
            </tr>
            <tr>
                <td>Nama Kapal</td>
                <td>:</td>
                <td><?= $ship->name ?? '-' ?></td>
            </tr>
            <tr>
                <td>Owner</td>
                <td>:</td>
                <td><?= $ship->owner ?? '-' ?></td>
            </tr>
            <tr>
                <td>Lokasi Inspeksi</td>
                <td>:</td>
                <td><?= strtoupper(($report->location ?? '') . ($report->city ? ', ' . $report->city : '') . ($report->province ? ', ' . $report->province : '') ?: '-') ?></td>
            </tr>
            <tr>
                <td>Tanggal Inspeksi</td>
                <td>:</td>
                <td><?= $report->date_of_measurement ? strtoupper(date('d F Y', strtotime($report->date_of_measurement)) . ' s/d ' . ($report->end_date_measurement ? date('d F Y', strtotime($report->end_date_measurement)) : date('d F Y', strtotime($report->date_of_measurement)))) : '-' ?></td>
            </tr>
        </table>

        <?php
        $logoUrl = $report->user->company->logo_resized ? Storage::disk('digitalocean')->temporaryUrl($report->user->company->logo_resized, now()->addMinutes(5)) : (
                   $report->user->company->logo ? Storage::disk('digitalocean')->temporaryUrl($report->user->company->logo, now()->addMinutes(5)) :
                   Storage::disk('digitalocean')->temporaryUrl('uploads/company_logos/logobki_new.png', now()->addMinutes(5))
                 );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $logoUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $logoData = curl_exec($ch);
        curl_close($ch);

        if ($logoData !== false) {
            $logoBase64 = base64_encode($logoData);
            $logoDataUri = 'data:image/png;base64,' . $logoBase64;
        } else {
            $logoDataUri = '';
        }
        ?>

        <div class="logo-container">
            <?php if (!empty($logoDataUri)): ?>
                <img src="<?= $logoDataUri ?>" alt="Company Logo">
            <?php else: ?>
                <p>Logo not available</p>
            <?php endif; ?>
        </div>

        <h2 class="company-name">
            {!! strtoupper($report->user->company->name ?? '-') !!} <br>
            {!! strtoupper($report->user->company->branch ?? '') !!}
        </h2>
    </div>
</body>
</html>
