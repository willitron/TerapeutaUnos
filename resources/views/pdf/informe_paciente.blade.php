<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe del Paciente</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; margin: 40px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header img.logo { height: 80px; margin-bottom: 10px; }
        .info { margin-bottom: 20px; }
        .info .label { font-weight: bold; display: inline-block; width: 150px; }
        .report-box { border: 1px solid #ccc; padding: 10px; background: #fdfdfd; }
        .footer { margin-top: 40px; text-align: center; font-size: 10px; color: #666; }
        .signature { margin-top: 40px; text-align: right; }
        .signature img { height: 60px; }
    </style>
</head>
<body>
    <div class="header">
        <img src="file://{{ public_path('assets/images/tuturno.jpeg') }}" class="logo" alt="Logo" style="width: 150px" >
        <h2>Informe Médico del Paciente</h2>
    </div>

    <div class="info">
        <p><span class="label">Nombre completo:</span> {{ $report->patient->name }} {{ $report->patient->last_name }} {{ $report->patient->mother_last_name }}</p>
        <p><span class="label">Teléfono:</span> {{ $report->patient->phone }}</p>
        <p><span class="label">Correo electrónico:</span> {{ $report->patient->email }}</p>
        <p><span class="label">Fecha de Reporte:</span> {{ \Carbon\Carbon::parse($report->report_date)->format('d/m/Y') }}</p>
        <p><span class="label">Archivo:</span> {{ $report->report_file }}</p>
    </div>

    <div class="report-box">
        {!! $report->report !!}
    </div>

    <div class="signature">
        <p>__________________________</p>
        <img src="file://{{ public_path('images/firma.png') }}" alt="Firma">
        <p style="margin-top: 5px;">Firma del Profesional</p>
    </div>

    <div class="footer">
        Generado automáticamente | {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
