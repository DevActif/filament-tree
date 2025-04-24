<?php

namespace SolutionForest\FilamentTree\Components;

use Filament\Support\Components\ViewComponent;
use SolutionForest\FilamentTree\Data\TreeData;

class Tree extends ViewComponent
{
    protected string $view = 'filament-tree::components.tree.index';

    protected string $viewIdentifier = 'tree';

    protected int $maxDepth = 999;

    protected array $actions = [];

    public const LOADING_TARGETS = ['activeLocale'];

    public function __construct(HasTree $livewire)
    {
        $this->livewire($livewire);
    }

    public static function make(HasTree $livewire): static
    {
        $result = app(static::class, ['livewire' => $livewire]);

        $result->configure();

        return $result;
    }

    public function maxDepth(int $maxDepth): static
    {
        $this->maxDepth = $maxDepth;

        return $this;
    }

    public function actions(array $actions): static
    {
        $this->actions = $actions;

        return $this;
    }

    public function getMaxDepth(): int
    {
        return $this->maxDepth;
    }

    public function getActions(): array
    {
        return $this->actions;
    }

    public function getRecordKey(?TreeData $record): ?string
    {
        if (! $record) {
            return null;
        }

        return $record->id;
    }

    public function getParentKey(?TreeData $record): ?string
    {
        if (! $record) {
            return null;
        }

        return $record->parent_id;
    }

    protected HasTree $livewire;

    public function livewire(HasTree $livewire): static
    {
        $this->livewire = $livewire;

        return $this;
    }

    public function getLivewire(): HasTree
    {
        return $this->livewire;
    }
}
