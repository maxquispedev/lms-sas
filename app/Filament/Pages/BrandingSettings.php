<?php

namespace App\Filament\Pages;

use App\Models\BrandingSetting;
use App\Support\Branding\BrandingRepository;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;
use BackedEnum;
use UnitEnum;

class BrandingSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-photo';

    protected string $view = 'filament.pages.branding-settings';

    protected static string|UnitEnum|null $navigationGroup = 'Configuración';

    protected static ?string $navigationLabel = 'Branding';

    protected static ?int $navigationSort = 999;

    public ?array $data = [];

    public function mount(BrandingRepository $brandingRepository): void
    {
        $settings = $brandingRepository->get();

        $this->form->fill([
            'academy_name' => $settings->academy_name,
            'primary_color' => $settings->primary_color,
            'logo_path' => $settings->logo_path,
            'dark_logo_path' => $settings->dark_logo_path,
            'certificate_background_path' => $settings->certificate_background_path,
            'favicon_path' => $settings->favicon_path,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identidad')
                    ->description('Configura el logo y el nombre que se muestra a los alumnos.')
                    ->schema([
                        Grid::make()
                            ->columns(2)
                            ->schema([
                                TextInput::make('academy_name')
                                    ->label('Nombre')
                                    ->required()
                                    ->maxLength(120),
                                ColorPicker::make('primary_color')
                                    ->label('Color principal')
                                    ->helperText('Se usa como color primary en el área de alumnos.'),
                            ]),
                        Grid::make()
                            ->columns(3)
                            ->schema([
                                FileUpload::make('logo_path')
                                    ->label('Logo')
                                    ->disk('public')
                                    ->directory('branding')
                                    ->image()
                                    ->imageEditor()
                                    ->maxSize(2048)
                                    ->helperText('PNG/JPG/WebP. Máximo 2MB.'),
                                FileUpload::make('dark_logo_path')
                                    ->label('Logo (modo oscuro)')
                                    ->disk('public')
                                    ->directory('branding')
                                    ->image()
                                    ->imageEditor()
                                    ->maxSize(2048)
                                    ->helperText('PNG/JPG/WebP. Máximo 2MB.'),
                                FileUpload::make('favicon_path')
                                    ->label('Favicon')
                                    ->disk('public')
                                    ->directory('branding')
                                    ->image()
                                    ->maxSize(1024)
                                    ->helperText('PNG/ICO recomendado. Máximo 1MB.'),
                            ]),
                    ]),
                Section::make('Certificados')
                    ->description('Imagen de fondo para el certificado descargable (PDF).')
                    ->schema([
                        FileUpload::make('certificate_background_path')
                            ->label('Fondo del certificado')
                            ->disk('public')
                            ->directory('certificates')
                            ->image()
                            ->imageEditor()
                            ->maxSize(4096)
                            ->helperText('Recomendado: formato horizontal (A4 apaisado). PNG/JPG/WebP. Máximo 4MB.'),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(BrandingRepository $brandingRepository): void
    {
        $state = $this->form->getState();

        /** @var BrandingSetting $settings */
        $settings = BrandingSetting::query()->firstOrCreate(['id' => 1]);

        $settings->academy_name = (string) $state['academy_name'];
        $settings->primary_color = isset($state['primary_color']) && $state['primary_color'] !== ''
            ? (function (string $value): string {
                $normalized = strtolower(trim($value));

                return str_starts_with($normalized, '#')
                    ? $normalized
                    : "#{$normalized}";
            })((string) $state['primary_color'])
            : null;
        $settings->logo_alt = (string) $settings->academy_name;
        $settings->logo_path = isset($state['logo_path']) && $state['logo_path'] !== ''
            ? (string) $state['logo_path']
            : null;
        $settings->dark_logo_path = isset($state['dark_logo_path']) && $state['dark_logo_path'] !== ''
            ? (string) $state['dark_logo_path']
            : null;

        $settings->certificate_background_path = isset($state['certificate_background_path']) && $state['certificate_background_path'] !== ''
            ? (string) $state['certificate_background_path']
            : null;
        $settings->favicon_path = isset($state['favicon_path']) && $state['favicon_path'] !== ''
            ? (string) $state['favicon_path']
            : null;
        $settings->save();

        $brandingRepository->forgetCache();

        Notification::make()
            ->title('Branding actualizado')
            ->success()
            ->send();
    }

    public function getLogoUrl(BrandingRepository $brandingRepository): ?string
    {
        $settings = $brandingRepository->get();

        if (!$settings->logo_path) {
            return null;
        }

        return Storage::disk('public')->url($settings->logo_path);
    }
}

