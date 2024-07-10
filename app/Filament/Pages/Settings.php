<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;
    public ?array $data = [];
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.settings';

    public function mount()
    {
        $settings = Setting::whereIn('key', [
            'site_title',
            'site_description',
            'meta_title',
            'meta_description',
            'logo',
            'favicon',
        ])->get()->pluck('value', 'key')->toArray();

        $this->form->fill($settings);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            FileUpload::make('logo')->required()->image(),
            FileUpload::make('favicon')->required()->image()->imageEditor()
                ->imageEditorAspectRatios([
                    '1:1',
                ]),
            TextInput::make('site_title')->required()->maxLength(60),
            TextInput::make('site_description')->required()->maxLength(160),
            TextInput::make('meta_title')->required()->maxLength(60),
            TextInput::make('meta_description')->required()->maxLength(160),
        ])->columns(2)->statePath('data');
    }

    public function getFormActions()
    {
        return [
            Action::make('save')->submit('save')
        ];
    }

    public function save()
    {
        try {
            $data = $this->form->getState();
            // Lưu vào bảng settings
            Setting::updateOrCreate(
                ['key' => 'site_title'],
                ['value' => $data['site_title']]
            );

            Setting::updateOrCreate(
                ['key' => 'site_description'],
                ['value' => $data['site_description']]
            );

            Setting::updateOrCreate(
                ['key' => 'meta_title'],
                ['value' => $data['meta_title']]
            );

            Setting::updateOrCreate(
                ['key' => 'meta_description'],
                ['value' => $data['meta_description']]
            );

            Setting::updateOrCreate(
                ['key' => 'logo'],
                ['value' => $data['logo']]
            );

            Setting::updateOrCreate(
                ['key' => 'favicon'],
                ['value' => $data['favicon']]
            );
        } catch (\Throwable $th) {
            return dd($th);
        }
        Notification::make()->success()->title('Save !')->send();
    }
}
