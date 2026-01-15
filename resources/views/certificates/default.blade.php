<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado de Finalización</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px;
        }

        .certificate-container {
            background: white;
            width: 100%;
            height: 100%;
            padding: 60px;
            border: 8px solid #667eea;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        .certificate-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .certificate-title {
            font-size: 48px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 3px;
        }

        .certificate-subtitle {
            font-size: 24px;
            color: #764ba2;
            margin-bottom: 20px;
        }

        .certificate-body {
            text-align: center;
            margin: 60px 0;
        }

        .certificate-text {
            font-size: 20px;
            line-height: 1.8;
            color: #333;
            margin-bottom: 30px;
        }

        .student-name {
            font-size: 36px;
            font-weight: bold;
            color: #667eea;
            margin: 30px 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .course-name {
            font-size: 28px;
            font-weight: bold;
            color: #764ba2;
            margin: 20px 0;
        }

        .certificate-footer {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .date-section {
            text-align: left;
        }

        .date-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }

        .date-value {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }

        .signature-section {
            text-align: center;
            flex: 1;
        }

        .signature-line {
            border-top: 2px solid #667eea;
            width: 300px;
            margin: 0 auto 10px;
            padding-top: 10px;
        }

        .signature-label {
            font-size: 14px;
            color: #666;
        }

        .certificate-seal {
            text-align: center;
            margin: 40px 0;
        }

        .seal {
            display: inline-block;
            width: 120px;
            height: 120px;
            border: 4px solid #667eea;
            border-radius: 50%;
            line-height: 112px;
            font-size: 48px;
            color: #667eea;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="certificate-header">
            <div class="certificate-title">Certificado de Finalización</div>
            <div class="certificate-subtitle">Este certificado acredita que</div>
        </div>

        <div class="certificate-body">
            <div class="certificate-text">
                Se certifica que
            </div>
            <div class="student-name">
                {{ $user->name }}
            </div>
            <div class="certificate-text">
                ha completado exitosamente el curso
            </div>
            <div class="course-name">
                "{{ $course->title }}"
            </div>
            <div class="certificate-seal">
                <div class="seal">✓</div>
            </div>
        </div>

        <div class="certificate-footer">
            <div class="date-section">
                <div class="date-label">Fecha de emisión:</div>
                <div class="date-value">{{ $date }}</div>
            </div>
            <div class="signature-section">
                <div class="signature-line"></div>
                <div class="signature-label">Firma y Sello</div>
            </div>
        </div>
    </div>
</body>
</html>
