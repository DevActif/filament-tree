<?php

namespace SolutionForest\FilamentTree\Actions;

use Closure;
use Filament\Actions\Concerns\HasMountableArguments;
use Filament\Actions\Action as BaseAction;
use SolutionForest\FilamentTree\Data\TreeData;
use SolutionForest\FilamentTree\Concern\BelongsToTree;
use SolutionForest\FilamentTree\Concern\Actions\HasTree;

class Action extends BaseAction implements HasTree
{
    use BelongsToTree;
    use HasMountableArguments;

    public const BUTTON_VIEW = 'filament-tree::actions.button-action';

    public const GROUPED_VIEW = 'filament-tree::actions.grouped-action';

    public const ICON_BUTTON_VIEW = 'filament-tree::actions.icon-button-action';

    public const LINK_VIEW = 'filament-tree::actions.link-action';

    public function getLivewireCallMountedActionName(): string
    {
        return 'callMountedTreeAction';
    }

    public function getLivewireClickHandler(): ?string
    {
        if (! $this->isLivewireClickHandlerEnabled()) {
            return null;
        }

        if (is_string($this->action)) {
            return $this->action;
        }

        return "mountTreeAction('{$this->getName()}')";
    }

    /**
     * @return array<mixed>
     */
    protected function resolveDefaultClosureDependencyForEvaluationByName(string $parameterName): array
    {
        return match ($parameterName) {
            'record' => [$this->getTreeRecord()],
            'tree' => [$this->getTree()],
            default => parent::resolveDefaultClosureDependencyForEvaluationByName($parameterName),
        };
    }

    /**
     * @return array<mixed>
     */
    protected function resolveDefaultClosureDependencyForEvaluationByType(string $parameterType): array
    {
        $record = $this->getTreeRecord();

        if (! $record) {
            return parent::resolveDefaultClosureDependencyForEvaluationByType($parameterType);
        }

        return match ($parameterType) {
            TreeData::class, $record::class => [$record],
            default => parent::resolveDefaultClosureDependencyForEvaluationByType($parameterType),
        };
    }

    public function prepareModalAction(BaseAction $action): BaseAction
    {
        $action = parent::prepareModalAction($action);

        if (! $action instanceof Action) {
            return $action;
        }

        return $action
            ->tree($this->getTree())
            ->treeRecord($this->getTreeRecord());
    }

    protected function getDefaultEvaluationParameters(): array
    {
        return collect(['record', 'model', 'tree'])
            ->flip()
            ->map(fn ($v, $name) => $this->resolveDefaultClosureDependencyForEvaluationByName($name)[0] ?? null)
            ->toArray();
    }

    protected TreeData|Closure|null $treeRecord = null;

    public function treeRecord(TreeData|Closure|null $record): static
    {
        $this->treeRecord = $record;

        return $this;
    }

    public function getTreeRecord(): ?TreeData
    {
        return $this->evaluate($this->treeRecord);
    }
}
