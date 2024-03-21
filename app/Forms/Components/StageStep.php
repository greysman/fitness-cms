<?php

namespace App\Forms\Components;

use Closure;
use Filament\Forms\Components\Contracts\CanConcealComponents;
use Filament\Forms\Components\Field;
use Illuminate\Support\Str;

class StageStep extends Field implements CanConcealComponents
{
    protected ?Closure $afterValidation = null;

    protected ?Closure $beforeValidation = null;

    protected string $view = 'forms.components.stage-step';

    protected string | Closure | null $description = null;

    protected string | Closure | null $icon = null;

    protected string | Closure | null $value = null;

    protected string | Closure | null $iconDone = null;

    final public function __construct(string $name)
    {
        $this->name($name);
        $this->statePath($name);
    }

    public static function make(string $name): static
    {
        $static = app(static::class, ['name' => $name]);
        $static->configure();

        return $static;
    }

    public function afterValidation(?Closure $callback): static
    {
        $this->afterValidation = $callback;

        return $this;
    }

    public function beforeValidation(?Closure $callback): static
    {
        $this->beforeValidation = $callback;

        return $this;
    }

    public function description(string | Closure | null $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function value(string | Closure | null $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function icon(string | Closure | null $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function callAfterValidation(): void
    {
        $this->evaluate($this->afterValidation);
    }

    public function callBeforeValidation(): void
    {
        $this->evaluate($this->beforeValidation);
    }

    public function getDescription(): ?string
    {
        return $this->evaluate($this->description);
    }

    public function getIcon(): ?string
    {
        return $this->evaluate($this->icon);
    }

    public function getColumnsConfig(): array
    {
        return $this->columns ?? $this->getContainer()->getColumnsConfig();
    }

    public function canConcealComponents(): bool
    {
        return true;
    }
}
