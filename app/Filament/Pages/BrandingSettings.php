<?php

namespace App\Filament\Pages;

use App\Models\BrandingSetting;
use App\Support\Branding\BrandingRepository;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
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
            'logo_alt' => $settings->logo_alt,
            'logo_path' => $settings->logo_path,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identidad')
                    ->description('Configura el logo y el nombre que se muestra a los alumnos.')
                    ->schema([
                        TextInput::make('academy_name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(120),
                        TextInput::make('logo_alt')
                            ->label('Texto alternativo (alt)')
                            ->required()
                            ->maxLength(120),
                        FileUpload::make('logo_path')
                            ->label('Logo')
                            ->disk('public')
                            ->directory('branding')
                            ->image()
                            ->imageEditor()
                            ->maxSize(2048)
                            ->helperText('PNG/JPG/WebP. Máximo 2MB. Se mostrará en el menú del alumno.'),
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
        $settings->logo_alt = (string) $state['logo_alt'];
        $settings->logo_path = isset($state['logo_path']) && $state['logo_path'] !== ''
            ? (string) $state['logo_path']
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

