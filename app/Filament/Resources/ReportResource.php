<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportResource\Pages;
use App\Filament\Resources\ReportResource\RelationManagers;
use App\Models\Report;
use App\Models\User;
use Filament\Actions\ExportAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Actions\ButtonAction;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

use Illuminate\Support\Facades\Gate;


class ReportResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Administración';

    protected static ?string $navigationLabel = 'Reportes';

    protected static ?string $modelLabel = 'Reportes';

    public static function form(Form $form): Form
    {


        return $form
            ->schema([
                Forms\Components\Select::make('patient_id')
                    ->label('Paciente')
                    // ->relationship('patient', 'name')
                    ->options(User::where('user_type', 'patient')->pluck('name', 'id')->toArray())
                    // ->searchable()
                    ->required(),
                Forms\Components\DatePicker::make('report_date')
                    ->label('Fecha de reporte')
                    ->default(now()->toDateString())
                    ->readOnly()
                    ->required(),
                Forms\Components\RichEditor::make('report')
                    ->label('Reporte')
                    ->required()
                    ->disableToolbarButtons([
                        'attachFiles',
                        'blockquote',
                        'codeBlock',
                        'h2',
                        'h3',
                        'link',
                        'redo',
                        'strike',
                        'undo',

                        ])
                    ->columnSpanFull()
                    ->validationMessages([
                        'required' => 'Ingrese el reporte del paciente.',
                    ])
                    ,
                Forms\Components\TextInput::make('report_file')
                    ->label('Archivo de reporte')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('patient.name')
                    ->label('Paciente')
                    ->icon('heroicon-m-user')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('report_date')
                    ->label('Fecha de reporte')
                    ->icon('heroicon-m-calendar-days')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('report_file')
                    ->label('Archivo de reporte')
                    ->icon('heroicon-m-document')
                    ->searchable(),
                Tables\Columns\TextColumn::make('report')
                    ->label('Reporte')
                    ->html()
                    ->limit(30)
                    ->icon('heroicon-m-document-text')
                    ->iconColor('success')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->label('Eliminar'),
                // ExportPdfAction::make()->label('Exportar PDF'),
                // ExportAction::make()->label('Exportar'),

                Tables\Actions\Action::make('exportar_pdf')
                ->label('PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('info')
                ->visible(fn () => true)  // aquí para pruebas, mostrar siempre
                ->action(function ($record) {
                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.informe_paciente', [
                        'report' => $record,
                    ]);
                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        'informe-' . $record->patient->name . '.pdf'
                    );
                }),


            ])
            ->bulkActions([
                    Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    ExportBulkAction::make()
                    ->exports([
                        ExcelExport::make()->withFilename('Informe'.date('Y-m-d') . ' - export')
                        ->fromTable()

                    ])
                ]),


            ])
            ->recordUrl(null);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReports::route('/'),
            'create' => Pages\CreateReport::route('/create'),
            'edit' => Pages\EditReport::route('/{record}/edit'),
        ];
    }
}



