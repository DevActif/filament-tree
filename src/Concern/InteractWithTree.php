<?php

namespace SolutionForest\FilamentTree\Concern;

use Closure;
use Illuminate\Support\HtmlString;
use SolutionForest\FilamentTree\Data\TreeData;
use SolutionForest\FilamentTree\Concern\HasActions;
use SolutionForest\FilamentTree\Concern\HasEmptyState;
use SolutionForest\FilamentTree\Concern\HasHeading;
use SolutionForest\FilamentTree\Concern\HasRecords;
use SolutionForest\FilamentTree\Support\Utils;

trait InteractWithTree
{
    use HasActions;
    use HasEmptyState;
    use HasHeading;
    // use HasRecords;

    protected bool $hasMounted = false;

    protected Tree $tree;

    public function bootedInteractWithTree()
    {
        $tree = $this->getTree();
        $this->tree = $tree->configureUsing(
            Closure::fromCallable([static::class, 'tree']),
            fn (): Tree => static::tree($tree)->maxDepth(static::getMaxDepth()),
        );

        $this->cacheTreeActions();
        $this->cacheTreeEmptyStateActions();

        $this->tree->actions(array_values($this->getCachedTreeActions()));

        if ($this->hasMounted) {
            return;
        }

        $this->hasMounted = true;
    }

    public function mountInteractsWithTree(): void {}

    protected function getCachedTree(): Tree
    {
        return $this->tree;
    }

    protected function getTree(): Tree
    {
        return Tree::make($this);
    }

    public function getTreeRecordTitle(?TreeData $record = null): string
    {
        if (! $record) {
            return '';
        }

        return $record->title;
    }

    public function getTreeRecordDescription(?TreeData $record = null): string|HtmlString|null
    {
        if (! $record) {
            return '';
        }

        return $record->description;
    }

    public function getTreeRecordIcon(?TreeData $record = null): ?string
    {
        if (! $record) {
            return null;
        }

        return $record->icon;
    }

    public function getRecordKey(?TreeData $record): ?string
    {
        return $this->getCachedTree()->getRecordKey($record);
    }

    public function getParentKey(?TreeData $record): ?string
    {
        return $this->getCachedTree()->getParentKey($record);
    }

    public function getNodeCollapsedState(?TreeData $record = null): bool
    {
        return false;
    }

    public function getTreeRootLevelKey(): null|string|int
    {
        return Utils::defaultParentId();
    }

    private function unnestArray(array &$result, array $current, $parent): void
    {
        foreach ($current as $index => $item) {
            $key = data_get($item, 'id');
            $result[$key] = [
                'parent_id' => $parent,
                'order' => $index + 1,
            ];
            if (isset($item['children']) && count($item['children'])) {
                $this->unnestArray($result, $item['children'], $key);
            }
        }
    }
}
