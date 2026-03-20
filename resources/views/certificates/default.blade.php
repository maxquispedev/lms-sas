<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Certificado de Finalización</title>
    <style>
        /* Elimina los márgenes automáticos del PDF */
        @page {
            margin: 0px;
            size: 297mm 210mm; /* A4 Horizontal */
        }

        /* Dompdf necesita que body y html tengan 100% explícito */
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: 'Times New Roman', Times, serif;
        }

        /* EL TRUCO PARA EL FONDO: Usar una imagen real, no background de CSS */
        .bg-image {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1000;
        }

        /* Estructura de tabla principal para centrar */
        .main-table {
            width: 100%;
            height: 100%;
            border-collapse: collapse;
        }

        .main-cell {
            vertical-align: middle;
            text-align: center;
            /* Empuja el contenido para no pisar la onda azul de la izquierda */
            padding-left: 80mm; 
            padding-right: 20mm;
        }

        /* Tipografía */
        .title {
            margin-top: 230px;
            font-size: 38px;
            font-weight: bold;
            color: #2c3e50;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin-bottom: 20px;
        }

        .subtitle {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
        }

        .student {
            font-size: 32px;
            font-weight: bold;
            color: #1a1a1a;
            text-transform: uppercase;
            margin-bottom: 30px;
        }

        .course-label {
            font-size: 16px;
            color: #333;
            margin-bottom: 10px;
        }

        .course-name {
            font-size: 22px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 50px;
        }

        /* Tabla anidada para la fecha alineada a la derecha */
        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer-cell {
            text-align: right;
            font-size: 14px;
            color: #333;
        }

        .date-value {
            font-weight: bold;
            color: #2c3e50;
        }

        /* Código único — esquina superior derecha (Dompdf soporta position: fixed) */
        .certificate-code {
            position: fixed;
            top: 12mm;
            right: 18mm;
            font-size: 11px;
            font-weight: bold;
            color: #2c3e50;
            letter-spacing: 0.5px;
            z-index: 1000;
            text-align: right;
            line-height: 1.3;
        }

        .certificate-code-label {
            font-size: 9px;
            font-weight: normal;
            color: #555;
            text-transform: uppercase;
        }
    </style>
</head>
<body>

    @if(!empty($backgroundImage))
        <img src="{{ $backgroundImage }}" class="bg-image" />
    @endif

    <div class="certificate-code" aria-hidden="true">
        <div class="certificate-code-label">Código de verificación</div>
        <div>{{ $certificateCode }}</div>
    </div>

    <table class="main-table">
        <tr>
            <td class="main-cell">
                
                <div class="title">CONSTANCIA</div>
                <div class="subtitle">Otorgada a:</div>
                
                <div class="student">{{ $user->name }}</div>
                
                <div class="course-label">Por haber completado satisfactoriamente el curso:</div>
                <div class="course-name">{{ $course->title }}</div>

                <table class="footer-table">
                    <tr>
                        <td class="footer-cell">
                            Fecha de emisión: <span class="date-value">{{ $date }}</span>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>

</body>
</html>