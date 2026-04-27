<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>ID Card - {{ $user->application_number }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            padding: 20px;
        }
        .card {
            width: 100%;
            border: 2px solid #444;
            border-radius: 10px;
            overflow: hidden;
            background: #fff;
            position: relative;
        }
        .header {
            background: #1e293b;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 14px;
            opacity: 0.8;
        }
        .content {
            padding: 30px;
            display: flex;
        }
        .photo-box {
            float: right;
            width: 150px;
            height: 180px;
            border: 1px solid #ccc;
            text-align: center;
            background: #f9fafb;
        }
        .photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .details {
            float: left;
            width: 70%;
        }
        .detail-row {
            margin-bottom: 15px;
        }
        .label {
            font-weight: bold;
            color: #64748b;
            font-size: 12px;
            text-transform: uppercase;
            display: block;
        }
        .value {
            font-size: 16px;
            color: #1e293b;
            font-weight: 600;
        }
        .footer {
            clear: both;
            border-top: 1px solid #eee;
            padding: 20px;
            background: #f8fafc;
            font-size: 12px;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            color: rgba(0, 0, 0, 0.05);
            z-index: -1;
            white-space: nowrap;
        }
        .signature-section {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            text-align: center;
            width: 200px;
        }
        .sig-line {
            border-top: 1px solid #333;
            margin-top: 50px;
            padding-top: 5px;
        }
        .instructions {
            margin-top: 20px;
            padding: 15px;
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 5px;
        }
        .instructions h3 {
            margin-top: 0;
            font-size: 14px;
            color: #92400e;
        }
        .instructions ul {
            padding-left: 20px;
            margin-bottom: 0;
        }
        .instructions li {
            margin-bottom: 5px;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="watermark">VERIFIED</div>
            
            <div class="header">
                <h1>ADMIT CARD / ID CARD</h1>
                <p>National Recruitment Examination {{ date('Y') }}</p>
            </div>

            <div class="content" style="overflow: hidden;">
                <div class="details">
                    <div class="detail-row">
                        <span class="label">Candidate Name</span>
                        <span class="value">{{ $application->full_name ?? $user->full_name ?? $user->name }}</span>
                    </div>

                    <div class="detail-row">
                        <span class="label">Application Number</span>
                        <span class="value">{{ $user->application_number }}</span>
                    </div>

                    <div class="detail-row">
                        <span class="label">Examination Name</span>
                        <span class="value">{{ $exam->title ?? $application->exam_name ?? 'N/A' }}</span>
                    </div>

                    <div class="detail-row">
                        <span class="label">Date of Birth</span>
                        <span class="value">{{ \Carbon\Carbon::parse($application->dob ?? $user->dob ?? $user->date_of_birth)->format('d M, Y') }}</span>
                    </div>

                    <div class="detail-row">
                        <span class="label">Roll Number</span>
                        <span class="value">ROLL-{{ $user->id }}-{{ date('Y') }}</span>
                    </div>
                </div>

                <div class="photo-box">
                    @if(isset($application->photo) && file_exists(public_path('storage/' . $application->photo)))
                        <img src="{{ public_path('storage/' . $application->photo) }}" alt="Candidate Photo">
                    @else
                        <div style="padding-top: 70px; color: #999;">PHOTO</div>
                    @endif
                </div>
            </div>

            <div class="footer">
                <div style="overflow: hidden;">
                    <div style="float: left; width: 50%;">
                        <div class="detail-row">
                            <span class="label">Exam Date</span>
                            <span class="value">To be Announced</span>
                        </div>
                    </div>
                    <div style="float: right; width: 40%; text-align: center;">
                        <div class="signature-box">
                            @if(isset($application->signature) && file_exists(public_path('storage/' . $application->signature)))
                                <img src="{{ public_path('storage/' . $application->signature) }}" style="height: 50px; width: auto;" alt="Signature">
                            @else
                                <div style="height: 50px;"></div>
                            @endif
                            <div class="sig-line">Candidate's Signature</div>
                        </div>
                    </div>
                </div>

                <div class="instructions">
                    <h3>Important Instructions</h3>
                    <ul>
                        <li>Please bring this Admit Card to the examination center without fail.</li>
                        <li>Carry an original Photo ID proof (Aadhar Card, PAN Card, or Voter ID).</li>
                        <li>Reach the examination center at least 30 minutes before the reporting time.</li>
                        <li>Calculators, mobile phones, or any electronic gadgets are strictly prohibited.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
