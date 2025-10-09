<?php
/**
 * @var \App\Models\Report $report
 */
?>
<div style="padding: 0 !important">
    <table style="width:80%; margin: 0 auto;">
        <tr>
            <td style="width:120px; vertical-align: middle; margin-top: 0; margin-bottom: 0; padding-top: 0; padding-bottom: 0;">
                <?php
                $logo_url = $report->user->company->logo_resized ?
                    Storage::disk('digitalocean')->temporaryUrl($report->user->company->logo_resized, now()->addMinutes(5)) : (
                    $report->user->company->logo ?
                        Storage::disk('digitalocean')->temporaryUrl($report->user->company->logo, now()->addMinutes(5)) :
                        Storage::disk('digitalocean')->temporaryUrl('uploads/company_logos/logobki_new.png', now()->addMinutes(5))
                );

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $logo_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $logo = curl_exec($ch);
                curl_close($ch);

                $base64_logo = base64_encode($logo);
                ?>
                <img src="data:image/png;base64,<?= $base64_logo ?>" style="width: auto; height: 60px; vertical-align: middle;">
            </td>
            <td style="font-size: 16px; font-weight: bold; text-align: left; text-transform: uppercase; vertical-align: middle; padding-left: 15px;">
                {!! $report->user->company->name !!} {!! $report->user->company->branch !!}
            </td>
        </tr>
    </table>
    <hr>

    <table class="report_header_table" style="margin:0 auto; font-size: 12px;">
        <tr>
            <th colspan="7" class="text-center">
                <u>THICKNESS MEASUREMENT REPORT</u>
                <br>
                No. Laporan : <?=strtoupper($report->report_number ?? '');?>
            </th>
        </tr>
        <tr>
            <th colspan="7" style="padding-top: 8px;">
                GENERAL PARTICULARS
            </th>
        </tr>
        <tr>
            <td style="padding-top: 3px; padding-bottom: 3px; vertical-align: top;">Nama Kapal</td>
            <td style="vertical-align: top;width: 10px; padding-left: 30px;"> : </td>
            <td colspan="5" style="vertical-align: top;"><?=strtoupper($report->ship->name ?? '');?></td>
        </tr>
        <tr>
            <td style="padding-top: 3px; padding-bottom: 3px; vertical-align: top;">Pemilik Kapal</td>
            <td style="vertical-align: top;width: 10px; padding-left: 30px;"> : </td>
            <td colspan="5" style="vertical-align: top;"><?=strtoupper($report->ship->owner ?? '-');?></td>
        </tr>
        <tr>
            <td style="padding-top: 3px; padding-bottom: 3px; vertical-align: top;">Alamat Pemilik</td>
            <td style="vertical-align: top;width: 10px; padding-left: 30px;"> : </td>
            <td colspan="5" style="vertical-align: top;"><?=strtoupper($report->ship->owner_city ?? '-');?></td>
        </tr>
        <tr>
            <td style="padding-top: 3px; padding-bottom: 3px; vertical-align: top;">Jenis Kapal</td>
            <td style="vertical-align: top;width: 10px; padding-left: 30px;"> : </td>
            <td colspan="5" style="vertical-align: top;"><?=strtoupper($report->ship->ship_type ?? '');?></td>
        </tr>
        <tr>
            <td style="padding-top: 3px; padding-bottom: 3px; vertical-align: top;">Nomor Registrasi Kapal</td>
            <td style="vertical-align: top;width: 10px; padding-left: 30px;"> : </td>
            <td colspan="5" style="vertical-align: top;"><?=strtoupper($report->ship->no_reg ?? '-');?></td>
        </tr>
        <tr>
            <td style="padding-top: 3px; padding-bottom: 3px; vertical-align: top;">Klasifikasi</td>
            <td style="vertical-align: top;width: 10px; padding-left: 30px;"> : </td>
            <td colspan="5" style="vertical-align: top;"><?=strtoupper($report->ship->classification ?? '-');?></td>
        </tr>
        <tr>
            <td style="padding-top: 3px; padding-bottom: 3px; vertical-align: top;">Jenis Laporan</td>
            <td style="vertical-align: top;width: 10px; padding-left: 30px;"> : </td>
            <td colspan="5" style="vertical-align: top;">{{strtoupper($report->kind_of_survey ?? '')}}</td>
        </tr>
        <tr>
            <td style="padding-top: 3px; padding-bottom: 3px; vertical-align: top;">Perusahaan Pelaksana Pengukuran<br>Ketebalan</td>
            <td style="vertical-align: top;width: 10px; padding-left: 30px;"> : </td>
            <td colspan="5" style="vertical-align: top;">{!!strtoupper($report->user->company->name ?? '-')!!}</td>
        </tr>
        <tr>
            <td style="padding-top: 3px; padding-bottom: 3px; vertical-align: top;">Operator / Inspektor</td>
            <td style="vertical-align: top;width: 10px; padding-left: 30px;"> : </td>
            <td colspan="5" style="vertical-align: top;">
                <?=strtoupper($report->user->name ?? '-');?>
            </td>
        </tr>
        <tr>
            <td style="padding-top: 3px; padding-bottom: 3px; vertical-align: top;">Kualifikasi Operator / Inspektor</td>
            <td style="vertical-align: top;width: 10px; padding-left: 30px;"> : </td>
            <td colspan="5" style="vertical-align: top;">{{strtoupper($report->user->qualification ?? '-')}}</td>
        </tr>
        <tr>
            <td style="padding-top: 3px; padding-bottom: 3px; vertical-align: top;">Tempat Inspeksi</td>
            <td style="vertical-align: top;width: 10px; padding-left: 30px;"> : </td>
            <td colspan="5" style="vertical-align: top;">
                {{ strtoupper($report->location ?? '-') }},
                {{ strtoupper($report->city ?? '') }},
                {{ strtoupper($report->province ?? '') }}
            </td>
        </tr>
        <tr>
            <td style="padding-top: 3px; padding-bottom: 3px; vertical-align: top;">Tanggal Inspeksi</td>
            <td style="vertical-align: top;width: 10px; padding-left: 30px;"> : </td>
            <td colspan="5" style="vertical-align: top;">
                {{ strtoupper($report->date_of_measurement ? date('d-m-Y', strtotime($report->date_of_measurement)) : '-') }}
                @if($report->date_of_measurement && $report->end_date_measurement)
                    s/d
                    {{ strtoupper(date('d-m-Y', strtotime($report->end_date_measurement))) }}
                @endif
            </td>
        </tr>
        <tr>
            <td style="vertical-align: top; padding-top: 3px; padding-bottom: 3px;">Detail Peralatan</td>
            <td style="vertical-align: top;width: 10px; padding-left: 30px;"> : </td>
            <td colspan="5" style="vertical-align: top;">
                <table class="table-borderless" style="border-collapse: collapse; width: 100%;">
                    <tr>
                        <td style="padding: 0; text-align: left;">Nama</td>
                        <td style="width: 10px; padding-left: 5px;"> : </td>
                        <td style="padding: 0;">{{ strtoupper($report->equipment->name ?? '-') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 0; text-align: left;">Manufaktur</td>
                        <td style="width: 10px; padding-left: 5px;"> : </td>
                        <td style="padding: 0;">{{ strtoupper($report->equipment->manufactur ?? '-') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 0; text-align: left;">Model</td>
                        <td style="width: 10px; padding-left: 5px;"> : </td>
                        <td style="padding: 0;">{{ strtoupper($report->equipment->model ?? '-') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 0; text-align: left;">Serial</td>
                        <td style="width: 10px; padding-left: 5px;"> : </td>
                        <td style="padding: 0;">{{ strtoupper($report->equipment->serial ?? '-') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 0; text-align: left;">Toleransi</td>
                        <td style="width: 10px; padding-left: 5px;"> : </td>
                        <td style="padding: 0;">± {{ strtoupper($report->equipment->tolerancy ?? '-') }} MM</td>
                    </tr>
                    <tr>
                        <td style="padding: 0; text-align: left;">Tipe Probe</td>
                        <td style="width: 10px; padding-left: 5px;"> : </td>
                        <td style="padding: 0;">{{ strtoupper($report->equipment->probe_type ?? '-') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding-top: 3px; padding-bottom: 3px; vertical-align: top;">Kriteria Penerimaan</td>
            <td style="vertical-align: top;width: 10px; padding-left: 30px;"> : </td>
            <td colspan="5" style="vertical-align: top;">BKI RULES</td>
        </tr>
    </table>

    <table style="width: 100%; margin-top: 0px; font-size: 12px;">
        <?php $form = $report->form()->first();?>
        <tr>
            <td style="width: 33%; text-align: center; vertical-align: top;">
                <p>Inspector/Operator Signature</p>
                <?= $form ? signature($form?->id, 'operator', 'pdf') : '-'; ?>
            </td>
            <td style="width: 33%; text-align: center; vertical-align: top;">
                <p>Supervisor Signature</p>
                <?= $form ? signature($form?->id, 'supervisor', 'pdf') : '-'; ?>
            </td>
            <td style="width: 33%; text-align: center; vertical-align: top;">
                <p>Surveyor Signature</p>
                <?= $form ? signature($form?->id, 'surveyor', 'pdf') : '-'; ?>
            </td>
        </tr>
    </table>
</div>

<div style="position: absolute; bottom: 0; left: 0; font-size: 12px;">
    <p><i>*Dokumen ini ditandatangani secara digital</i></p>
</div>

