<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Application Form - {{ $application->user->application_number }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            font-size: 13px;
        }
        .container {
            width: 100%;
            padding: 10px;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #1e293b;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header-title {
            font-size: 20px;
            font-weight: bold;
            color: #1e293b;
            text-transform: uppercase;
            margin: 0;
        }
        .header-subtitle {
            font-size: 12px;
            color: #64748b;
            margin: 4px 0 0 0;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #1e293b;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 4px;
            margin-top: 20px;
            margin-bottom: 12px;
            text-transform: uppercase;
        }
        .main-info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .details-col {
            width: 75%;
            vertical-align: top;
        }
        .photo-col {
            width: 25%;
            text-align: right;
            vertical-align: top;
        }
        .photo-box {
            display: inline-block;
            width: 130px;
            height: 160px;
            border: 1px solid #cbd5e1;
            background-color: #f8fafc;
            text-align: center;
            vertical-align: middle;
        }
        .photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .photo-placeholder {
            padding-top: 65px;
            font-size: 11px;
            color: #94a3b8;
            font-weight: bold;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table th, .info-table td {
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }
        .info-table th {
            width: 30%;
            font-weight: bold;
            color: #475569;
            font-size: 12px;
        }
        .info-table td {
            color: #0f172a;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .data-table th, .data-table td {
            border: 1px solid #e2e8f0;
            padding: 8px 10px;
            text-align: left;
        }
        .data-table th {
            background-color: #f1f5f9;
            color: #334155;
            font-weight: bold;
            width: 35%;
        }
        .payment-box {
            border: 1px solid #b91c1c;
            background-color: #fef2f2;
            border-radius: 6px;
            padding: 12px;
            margin-top: 15px;
        }
        .payment-box.paid {
            border: 1px solid #15803d;
            background-color: #f0fdf4;
        }
        .payment-status-badge {
            display: inline-block;
            padding: 3px 8px;
            font-weight: bold;
            border-radius: 4px;
            font-size: 11px;
            text-transform: uppercase;
        }
        .payment-status-badge.success {
            background-color: #dcfce7;
            color: #166534;
        }
        .payment-status-badge.pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        .declaration-text {
            font-size: 11px;
            color: #475569;
            text-align: justify;
            margin-top: 15px;
            line-height: 1.5;
        }
        .footer-table {
            width: 100%;
            margin-top: 40px;
        }
        .signature-box {
            text-align: center;
            width: 180px;
        }
        .signature-img-box {
            height: 50px;
            border-bottom: 1px solid #333;
            margin-bottom: 6px;
            text-align: center;
        }
        .signature-img-box img {
            max-height: 48px;
            max-width: 100%;
            object-fit: contain;
        }
        .signature-label {
            font-size: 11px;
            font-weight: bold;
            color: #475569;
        }
        .text-right {
            text-align: right;
        }
        .text-muted {
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Main Header -->
        <table class="header-table">
            <tr>
                <td>
                    <h1 class="header-title">Application Form</h1>
                    <div class="header-subtitle">{{ $exam->title }}</div>
                </td>
                <td class="text-right" style="vertical-align: bottom;">
                    <div style="font-weight: bold; font-size: 14px; color: #1e293b;">APP ID: {{ $application->user->application_number }}</div>
                    <div class="text-muted" style="font-size: 11px; margin-top: 4px;">Submitted: {{ $application->submitted_at ? $application->submitted_at->format('d M Y, h:i A') : 'N/A' }}</div>
                </td>
            </tr>
        </table>

        <!-- Profile & Photo Row -->
        <table class="main-info-table">
            <tr>
                <td class="details-col">
                    <div class="section-title" style="margin-top: 0;">Personal Details</div>
                    <table class="info-table">
                        <tr>
                            <th>Full Name</th>
                            <td>{{ $application->full_name ?? $application->user->full_name }}</td>
                        </tr>
                        <tr>
                            <th>Date of Birth</th>
                            <td>{{ $application->dob ? \Carbon\Carbon::parse($application->dob)->format('d M Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Gender</th>
                            <td>{{ $application->gender ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Mobile Number</th>
                            <td>{{ $application->mobile ?? $application->user->mobile }}</td>
                        </tr>
                        <tr>
                            <th>Email Address</th>
                            <td>{{ $application->email ?? $application->user->email ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{ $application->address ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </td>
                <td class="photo-col">
                    <div class="photo-box">
                        @if($photoVal)
                            <img src="{{ \App\Models\Application::fileUrl($photoVal) }}" alt="Photo">
                        @else
                            <div class="photo-placeholder">PASTE<br>PHOTO HERE</div>
                        @endif
                    </div>
                </td>
            </tr>
        </table>

        <!-- Custom / Dynamic Form Fields -->
        <div class="section-title">Application Details</div>
        <table class="data-table">
            @foreach($fields as $field)
                @php
                    // Skip photo and signature files because they are rendered separately
                    $isPhoto = (str_contains(strtolower($field->name), 'photo') || str_contains(strtolower($field->label), 'photo')) && $field->type === 'file';
                    $isSig = (str_contains(strtolower($field->name), 'signature') || str_contains(strtolower($field->label), 'signature')) && $field->type === 'file';
                    if ($isPhoto || $isSig) continue;

                    $val = data_get($application->form_data, $field->name);
                @endphp
                <tr>
                    <th>{{ $field->label }}</th>
                    <td>
                        @if($field->type === 'file')
                            @if($val)
                                @if(is_array($val))
                                    @foreach($val as $filePath)
                                        <div style="margin-bottom: 4px;">
                                            <a href="{{ \App\Models\Application::fileUrl($filePath) }}" target="_blank" style="text-decoration: none; color: #1d4ed8;">View File ({{ basename($filePath) }})</a>
                                        </div>
                                    @endforeach
                                @else
                                    <a href="{{ \App\Models\Application::fileUrl($val) }}" target="_blank" style="text-decoration: none; color: #1d4ed8;">View File ({{ basename($val) }})</a>
                                @endif
                            @else
                                <span class="text-muted">Not Uploaded</span>
                            @endif
                        @else
                            @if(is_array($val))
                                {{ implode(', ', $val) }}
                            @else
                                {{ $val ?? 'N/A' }}
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>

        @unless($exam->module_type === 'vacancy')
            <!-- Payment Details -->
            <div class="section-title">Payment Information</div>
            @php
                $isPaid = ($payment?->status ?? 'pending') === 'paid';
            @endphp
            <div class="payment-box {{ $isPaid ? 'paid' : '' }}">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 50%; vertical-align: top;">
                            <div style="font-weight: bold; margin-bottom: 4px;">Transaction ID / Razorpay Payment ID:</div>
                            <div style="font-family: monospace; font-size: 12px; color: #334155;">{{ $payment?->transaction_id ?? $payment?->razorpay_payment_id ?? 'N/A' }}</div>
                            
                            <div style="font-weight: bold; margin-top: 10px; margin-bottom: 4px;">Payment Method:</div>
                            <div style="text-transform: uppercase; color: #334155;">{{ $payment?->payment_method ?? 'N/A' }}</div>
                        </td>
                        <td style="width: 50%; vertical-align: top; text-align: right;">
                            <div style="font-weight: bold; margin-bottom: 4px;">Amount Paid:</div>
                            <div style="font-size: 16px; font-weight: bold; color: #166534; margin-bottom: 10px;">â‚¹{{ number_format($payment?->amount ?? $exam->fee ?? 500, 2) }}</div>
                            
                            <span class="payment-status-badge {{ $isPaid ? 'success' : 'pending' }}">
                                {{ $payment?->status ?? 'pending' }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        @endunless

        <!-- Declaration & Signatures -->
        <div class="section-title">Declaration</div>
        <div class="declaration-text">
            I hereby declare that all the statements made in this application form are true, complete, and correct to the best of my knowledge and belief. I understand that in the event of any information being found false or incorrect at any stage, my candidature/selection is liable to be cancelled/terminated without any notice or compensation.
        </div>

        <table class="footer-table">
            <tr>
                <td style="vertical-align: bottom;">
                    <div class="text-muted" style="font-size: 11px;">Downloaded via Portal on: {{ date('d M Y, h:i A') }}</div>
                    <div style="font-weight: bold; color: #166534; font-size: 11px; margin-top: 5px;">STATUS: VERIFIED APPLICATION</div>
                </td>
                <td class="text-right" style="width: 200px;">
                    <table class="signature-box" style="margin-left: auto;">
                        <tr>
                            <td>
                                <div class="signature-img-box">
                                    @if($sigVal)
                                        <img src="{{ \App\Models\Application::fileUrl($sigVal) }}" alt="Signature">
                                    @endif
                                </div>
                                <div class="signature-label">Candidate's Signature</div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
