<?php

namespace App\Forms\Components;

use App\Models\Store\Product;
use Closure;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;

class ProductSelect extends Select
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
            return $this->findProduct($search);
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
        return $this->evaluate($this->searchModel) ?? Product::class;
    }


    public function setQueryLimit(int $value): void
    {
        $this->queryLimit = $value;
    }


    public function findProduct(string $search): array
    {
        $items = $this->getSearchModel()::where('title', 'like', "%$search%")
            ->orWhere('sku', 'like', "%$search%")
            ->limit($this->queryLimit)
            ->get();

        return $items->mapWithKeys(function ($item) {
            return [$item->getKey() => $this->getCleanOptionString($item)];
        })->toArray();
    }


    public function getCleanOptionString(Model $entity): string
    {
        $data = [];
        if ($entity->type) {
            $data[] = $entity->type;
        }
        if ($entity->mainCategory) {
            $data[] = $entity->mainCategory->title;
        }
        if ($entity->price) {
            $data[] = $entity->price . __('products.table.price.suffix');
        }

        return view('filament.forms.components.product-select', [
                'title' => $entity->title,
                'data' => implode(" | ", $data),
                'image' => $entity->image_url,
            ])
            ->render();

    }
}
