<?php

namespace App\Filament\FilamentPageTemplates;

use Beier\FilamentPages\Contracts\FilamentPageTemplate;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use FilamentEditorJs\Forms\Components\EditorJs;
use Nuhel\FilamentCropper\Components\Cropper;

final class ServiceTemplate implements FilamentPageTemplate
{
    public static function title(): string
    {
        return __('filament-pages::filament-pages.filament.templates.items.simple_service');
    }

    public static function schema(): array
    {
        return [
            Card::make()
                ->schema([
                    // Cropper::make('image')
                    //     ->label(__('filament-pages::filament-pages.filament.form.image.label'))
                    //     ->modalSize('lg')
                    //     ->enableImageRotation()
                    //     ->rotationalStep(5)
                    //     ->enableImageFlipping()
                    //     ->enabledAspectRatios([
                    //         '3:2', '16:9', '1:1', '9:4'
                    //     ])
                    //     ->zoomable(true)
                    //     ->enableZoomButtons()
                    //     ->enableAspectRatioFreeMode()
                    //     ->imageCropAspectRatio('9:4'),
                    
                    EditorJs::make('content')
                        ->label(__('filament-pages::filament-pages.filament.form.content.label')),
                ]),
        ];
    }
}
