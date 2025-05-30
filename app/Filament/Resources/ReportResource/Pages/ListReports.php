<?php

namespace App\Filament\Resources\ReportResource\Pages;

use App\Filament\Resources\ReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;

class ListReports extends ListRecords
{
    protected static string $resource = ReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            ExportAction::make('exportar')
            ->label('Export Excel')
            ->color('success')
            ->exports([

                ExcelExport::make()->withFilename( 'Informes_' . date('Y-m-d') . '_export')
                ->fromTable()
            ]),

            Action::make('pdf')
            ->label('Generar PDF')
            ->color('info')
            ->requiresConfirmation()

//!RECORDAR ESTE CODIGO PARA POSTERIORES PROYECTOS NO ESTA EN LA DOCUMENTACION
            ->action(function () {
                // Obtiene la query con filtros aplicados desde la tabla
                $records = $this->getFilteredTableQuery()->get();

                $pdf = Pdf::loadView('pdf.informes', [
                    'reports' => $records,
                ]);

                return response()->streamDownload(
                    fn () => print($pdf->output()),
                    'informes-filtrados.pdf'
                );
            }),



        ];
    }
}
