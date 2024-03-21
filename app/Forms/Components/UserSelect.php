<?php

namespace App\Forms\Components;

use App\Models\User;
use Closure;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;

class UserSelect extends Select
{
    public Model | Closure | string | null $searchModel = null;


    public int $queryLimit = 20;


    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->allowHtml()
            ->searchable();

        $this->getSearchResultsUsing(function (string $search) {
            return $this->findUser($search);
        });

        $this->getOptionLabelUsing(function (string $value): string {
            $entity = $this->getSearchModel()::find($value);

            return $this->getCleanOptionString($entity);
        });
    }


    public function setSearchModel(Model | string $model): static
    {
        $this->searchModel = $model;
        
        return $this;
    }

    public function getSearchModel()
    {
        return $this->evaluate($this->searchModel) ?? User::class;
    }


    public function setQueryLimit(int $value): void
    {
        $this->queryLimit = $value;
    }


    public function findUser(string $search): array
    {
        $items = $this->getSearchModel()::where('name', 'like', "%$search%")
            ->orWhere('surname', 'like', "%$search%")
            ->orWhere('email', 'like', "%$search%")
            ->orWhere('phone', 'like', "%$search%")
            ->limit($this->queryLimit)
            ->get();

        return $items->mapWithKeys(function ($item) {
            return [$item->getKey() => $this->getCleanOptionString($item)];
        })->toArray();
    }


    public function getCleanOptionString(Model $entity): string
    {
        $contacts = [];
        if ($entity->phone) {
            $contacts[] = $entity->phone;
        }
        if ($entity->email) {
            $contacts[] = $entity->email;
        }

        return view('filament.forms.components.user-select', [
                'name' => $entity->fullname,
                'contacts' => implode(" | ", $contacts),
                'avatar' => $entity->avatar,
            ])
            ->render();

    }
}
