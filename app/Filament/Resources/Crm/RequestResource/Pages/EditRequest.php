<?php

namespace App\Filament\Resources\Crm\RequestResource\Pages;

use App\Filament\Resources\Crm\RequestResource;
use App\Models\Crm\Request;
use Filament\Forms;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRequest extends EditRecord
{
    protected static string $resource = RequestResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ActionGroup::make([
                Actions\Action::make('explore')
                    ->label(__('requests.actions.explore.label'))
                    ->icon('heroicon-o-search')
                    ->action(function (Forms\ComponentContainer $form) {
                        $this->data['stage_id'] = Request::STAGE_EXPLORE;
                        return $this->save();
                    }),
                Actions\Action::make('offer')
                    ->label(__('requests.actions.offer.label'))
                    ->icon('heroicon-o-arrow-right')
                    ->action(function () {
                        $this->data['stage_id'] = Request::STAGE_OFFER;
                        return $this->save();
                    }),
                Actions\Action::make('win')
                    ->label(__('requests.actions.win.label'))
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->action(function () {
                        $this->data['stage_id'] = Request::STAGE_WIN;
                        return $this->save();
                    }),
                Actions\Action::make('lost')
                    ->label(__('requests.actions.lost.label'))
                    ->color('danger')
                    ->icon('heroicon-o-x')
                    ->action(function () {
                        $this->data['stage_id'] = Request::STAGE_LOST;
                        return $this->save();
                    }),
            ]),
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
