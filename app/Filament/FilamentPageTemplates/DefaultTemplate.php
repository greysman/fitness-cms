<?php

namespace App\Filament\FilamentPageTemplates;

use Beier\FilamentPages\Contracts\FilamentPageTemplate;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use FilamentEditorJs\Forms\Components\EditorJs;
use Nuhel\FilamentCropper\Components\Cropper;

final class DefaultTemplate implements FilamentPageTemplate
{
    public static function title(): string
    {
        return __('filament-pages::filament-pages.filament.templates.items.simple_page');
    }

    public static function schema(): array
    {
        return [
            Card::make()
                ->schema([
                    EditorJs::make('content')
                        ->label(__('filament-pages::filament-pages.filament.form.content.label')),
                ]),
        ];
    }
}
