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
            font-family: 'Times New Roman', Times, serif;
            background: #f5f5f5;
            padding: 30px;
        }

        .certificate-container {
            background: white;
            width: 100%;
            min-height: 100vh;
            padding: 80px 100px;
            border: 15px solid #2c3e50;
            position: relative;
        }

        .certificate-title {
            text-align: center;
            font-size: 42px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 4px;
            color: #2c3e50;
            margin-bottom: 60px;
            padding-bottom: 20px;
            border-bottom: 3px solid #2c3e50;
        }

        .certificate-body {
            text-align: center;
            margin: 80px 0;
            line-height: 2;
        }

        .certificate-text {
            font-size: 18px;
            color: #333;
            margin-bottom: 30px;
        }

        .student-name {
            font-size: 32px;
            font-weight: bold;
            color: #2c3e50;
            margin: 40px 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .course-text {
            font-size: 18px;
            color: #333;
            margin: 40px 0 20px 0;
        }

        .course-name {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin: 20px 0 60px 0;
        }

        .certificate-footer {
            margin-top: 100px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .date-section {
            text-align: left;
        }

        .date-label {
            font-size: 16px;
            color: #333;
            margin-bottom: 10px;
        }

        .date-value {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
        }

        .signature-section {
            text-align: center;
        }

        .signature-line {
            border-top: 2px solid #2c3e50;
            width: 250px;
            margin: 0 auto 10px;
            padding-top: 5px;
        }

        .signature-name {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            margin-top: 5px;
        }

        .signature-label {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <h1 class="certificate-title">CERTIFICADO DE FINALIZACIÓN</h1>

        <div class="certificate-body">
            <p class="certificate-text">Este certificado se otorga a:</p>
            
            <div class="student-name">{{ $user->name }}</div>
            
            <p class="course-text">Por haber completado satisfactoriamente el curso:</p>
            
            <div class="course-name">{{ $course->title }}</div>
        </div>

        <div class="certificate-footer">
            <div class="date-section">
                <div class="date-label">Fecha de emisión:</div>
                <div class="date-value">{{ $date }}</div>
            </div>
            <div class="signature-section">
                <div class="signature-line"></div>
                <div class="signature-name">{{ $course->teacher->name ?? 'Instructor' }}</div>
                <div class="signature-label">Firma del Instructor</div>
            </div>
        </div>
    </div>
</body>
</html>
