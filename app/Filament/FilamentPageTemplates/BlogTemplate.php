<?php

namespace App\Filament\FilamentPageTemplates;

use Beier\FilamentPages\Contracts\FilamentPageTemplate;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use FilamentEditorJs\Forms\Components\EditorJs;
use Nuhel\FilamentCropper\Components\Cropper;

final class BlogTemplate implements FilamentPageTemplate
{
    public static function title(): string
    {
        return __('filament-pages::filament-pages.filament.templates.items.simple_blog');
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
