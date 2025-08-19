<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Slip Gaji</title>
    <style>
        /* --- General Style --- */
        body {
            font-family: 'Inter', 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            color: #111827;
            /* Dark Gray */
            background-color: #ffffff;
            margin: 0;
        }

        .container {
            padding: 25px;
            width: 100%;
            box-sizing: border-box;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        /* --- Header --- */
        .header {
            text-align: center;
            margin-bottom: 25px;
        }

        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .header p {
            margin: 4px 0 0;
            font-size: 12px;
            color: #6B7280;
            /* Medium Gray */
        }

        /* --- Employee Details --- */
        .employee-details {
            margin-bottom: 25px;
            padding: 15px;
            border: 1px solid #E5E7EB;
            /* Light Gray Border */
            border-radius: 8px;
        }

        .employee-details td {
            padding: 4px 0;
            font-size: 11px;
        }

        .employee-details strong {
            font-weight: 600;
            color: #374151;
            /* Darker Gray */
        }

        /* --- Payslip Body (Earnings & Deductions) --- */
        .payslip-body-table {
            border-spacing: 15px 0;
            border-collapse: separate;
        }

        .payslip-body-table>tbody>tr>td {
            width: 50%;
            vertical-align: top;
            padding: 0;
        }

        .section-table th,
        .section-table td {
            padding: 8px 0;
            border-bottom: 1px solid #F3F4F6;
            /* Very Light Gray */
        }

        .section-table th {
            text-align: left;
            font-size: 13px;
            font-weight: 600;
            padding-bottom: 10px;
            border-bottom: 1px solid #E5E7EB;
        }

        .total-row th,
        .total-row td {
            padding-top: 10px;
            font-weight: bold;
            border-top: 1px solid #E5E7EB;
            border-bottom: none;
        }

        /* --- Net Pay Section --- */
        .net-pay-section {
            margin-top: 25px;
        }

        .net-pay-table {
            width: 45%;
            float: right;
        }

        .net-pay-table td {
            padding: 12px;
            border-radius: 6px;
        }

        .net-pay-label {
            font-size: 12px;
            font-weight: bold;
            text-align: left;
        }

        .net-pay-amount {
            font-size: 14px;
            font-weight: bold;
            background-color: #F9FAFB;
            /* Very Light Gray Background */
            text-align: right;
        }

        /* --- Signature Section --- */
        .signature-section {
            margin-top: 60px;
        }

        .signature-section td {
            width: 50%;
            text-align: end !important;
            font-size: 11px;
            padding-top: 50px;
        }

        .signature-section .signature-line {
            display: inline-block;
            width: 200px;
            border-bottom: 1px solid #9CA3AF;
            /* Medium Gray Line */
        }
    </style>
</head>

<body>
    @if ($payslip && $payslip->employee)
        <div class="container">
            <div class="header">
                <h1>SLIP GAJI KARYAWAN</h1>
                <p>Periode: <strong>{{ date('F', mktime(0, 0, 0, $payslip->month, 10)) }} {{ $payslip->year }}</strong>
                </p>
            </div>

            <div class="employee-details">
                <table>
                    <tr>
                        <td style="width: 18%;"><strong>Nama Karyawan</strong></td>
                        <td style="width: 32%;">: {{ $payslip->employee->user?->name ?? 'N/A' }}</td>
                        <td style="width: 18%;"><strong>NIK</strong></td>
                        <td style="width: 32%;">: {{ $payslip->employee->nik ?? 'Belum Diisi' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Jabatan</strong></td>
                        <td>: {{ $payslip->employee->position ?? 'N/A' }}</td>
                        <td><strong>Departemen</strong></td>
                        <td>: IT Development (Dummy)</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Bergabung</strong></td>
                        <td>:
                            {{ $payslip->employee->join_date ? \Carbon\Carbon::parse($payslip->employee->join_date)->format('d F Y') : 'N/A' }}
                        </td>
                        <td><strong>Status Pajak</strong></td>
                        <td>: TK/0 (Dummy)</td>
                    </tr>
                    <tr>
                        <td><strong>Bank</strong></td>
                        <td>: {{ $payslip->employee->bank_account_details ?? 'BCA - 1234567890 (Dummy)' }}</td>
                        <td><strong>Tanggal Pembayaran</strong></td>
                        <td>: {{ $payslip->pay_date?->format('d F Y') ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>

            <table class="payslip-body-table">
                <tr>
                    <td>
                        <table class="section-table">
                            <tr>
                                <th colspan="2">Pendapatan (Earnings)</th>
                            </tr>
                            @foreach ($payslip->earnings as $key => $value)
                                <tr>
                                    <td>{{ $key }}</td>
                                    <td class="text-right">Rp {{ number_format($value, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            <tr class="total-row">
                                <td>Total Pendapatan (A)</td>
                                <td class="text-right">Rp {{ number_format($payslip->total_earnings, 0, ',', '.') }}
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table class="section-table">
                            <tr>
                                <th colspan="2">Potongan (Deductions)</th>
                            </tr>
                            @foreach ($payslip->deductions as $key => $value)
                                <tr>
                                    <td>{{ $key }}</td>
                                    <td class="text-right">Rp {{ number_format($value, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            <tr class="total-row">
                                <td>Total Potongan (B)</td>
                                <td class="text-right">Rp {{ number_format($payslip->total_deductions, 0, ',', '.') }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <div class="net-pay-section clearfix">
                <table class="net-pay-table">
                    <tr>
                        <td class="net-pay-label">GAJI BERSIH (A - B)</td>
                        <td class="net-pay-amount">Rp {{ number_format($payslip->net_pay, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>

            <table class="signature-section">
                <tr>
                    <td>
                        Disetujui Oleh,
                        <div class="" style="margin-top: 60px;"></div>

                        <strong>( Manajer HRD / Keuangan )</strong>
                    </td>
                </tr>
            </table>
        </div>
    @else
        <p style="text-align: center; color: #EF4444;">Error: Data slip gaji tidak dapat dimuat.</p>
    @endif
</body>

</html>
