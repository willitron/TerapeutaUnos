<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Informe de Reportes</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #333;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th, td {
            border: 1px solid #aaa;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            margin-top: 20px;
            color: #666;
        }
    </style>
</head>
<body>
    <h1>Informe de Reportes</h1>

    <table>
        <thead>
            <tr>
                <th>Paciente</th>
                <th>Fecha de Reporte</th>
                <th>Archivo</th>
                <th>Reporte</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $report)
                <tr>
                    <td>{{ $report->patient->name ?? 'Sin nombre' }}</td>
                    <td>{{ \Carbon\Carbon::parse($report->report_date)->format('d/m/Y') }}</td>
                    <td>{{ $report->report_file }}</td>
                    <td>{!! \Illuminate\Support\Str::limit(strip_tags($report->report), 100) !!}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generado automáticamente por el sistema | {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
