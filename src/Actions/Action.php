<?php

namespace SolutionForest\FilamentTree\Actions;

use Closure;
use Filament\Actions\Concerns\HasMountableArguments;
use Filament\Actions\Contracts\Groupable;
use Filament\Actions\MountableAction;
use Filament\Actions\StaticAction;
use SolutionForest\FilamentTree\Data\TreeData;

class Action extends MountableAction implements ActionHasTree, Groupable
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
            'record' => [$this->getRecord()],
            'tree' => [$this->getTree()],
            default => parent::resolveDefaultClosureDependencyForEvaluationByName($parameterName),
        };
    }

    /**
     * @return array<mixed>
     */
    protected function resolveDefaultClosureDependencyForEvaluationByType(string $parameterType): array
    {
        $record = $this->getRecord();

        if (! $record) {
            return parent::resolveDefaultClosureDependencyForEvaluationByType($parameterType);
        }

        return match ($parameterType) {
            TreeData::class, $record::class => [$record],
            default => parent::resolveDefaultClosureDependencyForEvaluationByType($parameterType),
        };
    }

    public function prepareModalAction(StaticAction $action): StaticAction
    {
        $action = parent::prepareModalAction($action);

        if (! $action instanceof Action) {
            return $action;
        }

        return $action
            ->tree($this->getTree())
            ->record($this->getRecord());
    }

    protected function getDefaultEvaluationParameters(): array
    {
        return collect(['record', 'model', 'tree'])
            ->flip()
            ->map(fn ($v, $name) => $this->resolveDefaultClosureDependencyForEvaluationByName($name)[0] ?? null)
            ->toArray();
    }

    protected TreeData|Closure|null $record = null;

    public function record(TreeData|Closure|null $record): static
    {
        $this->record = $record;

        return $this;
    }

    public function getRecord(): ?TreeData
    {
        return $this->evaluate($this->record);

    }
}
