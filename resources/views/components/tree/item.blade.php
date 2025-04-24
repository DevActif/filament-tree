@php use Illuminate\Database\Eloquent\Model; @endphp
@php use Filament\Facades\Filament; @endphp
@php use SolutionForest\FilamentTree\Components\Tree; @endphp
@props([
    'record',
    'containerKey',
    'tree',
    'title' => null,
    'icon' => null,
    'description' => null,
])
@php
    /** @var $record Model */
    /** @var $containerKey string */
    /** @var $tree Tree */

    $recordKey = $tree->getRecordKey($record);
    $parentKey = $tree->getParentKey($record);

    $children = $record->children;
    $collapsed = $this->getNodeCollapsedState($record);

    $actions = $tree->getActions();
@endphp

<li class="filament-tree-row dd-item" data-id="{{ $recordKey }}">
    <div wire:loading.remove.delay wire:target="{{ implode(',', Tree::LOADING_TARGETS) }}"
        @class([
            'rounded-lg border h-10',
            'mb-2',
            'flex w-full items-center gap-4',
            'border-gray-300 bg-white dark:border-white/10 dark:bg-gray-900',
        ])>

        <div class="dd-content dd-nodrag flex gap-1">

            <x-filament-tree::tree.item-display class="ml-1 rtl:mr-1" :record="$record"
                :title="$title" :icon="$icon" :description="$description" />

        </div>
        <div @class([
            'dd-item-btns h-full',
            'hidden' => !count($children),
            'flex items-center justify-center',
        ])>
            <button data-action="expand" @class(['hidden' => !$collapsed])>
                <x-heroicon-o-chevron-down class="text-gray-800 w-6 h-6" />
            </button>
            <button data-action="collapse" @class(['hidden' => $collapsed])>
                <x-heroicon-o-chevron-up class="text-gray-800 w-6 h-6" />
            </button>
        </div>

        @if (count($actions))
            <div class="dd-nodrag ml-auto mr-8 rtl:ml-4 rtl:mr-auto">
                <x-filament-tree::actions :actions="$actions" :record="$record" />
            </div>
        @endif
    </div>
    @if (count($children))
        <x-filament-tree::tree.list :records="$children" :containerKey="$containerKey" :tree="$tree"
            :collapsed="$collapsed" />
    @endif
    <div class="rounded-lg border border-gray-300 mb-2 w-full px-4 py-4 animate-pulse hidden"
        wire:loading.class.remove.delay="hidden"
        wire:target="{{ implode(',', Tree::LOADING_TARGETS) }}">
        <div class="h-4 bg-gray-300 rounded-md"></div>
    </div>
</li>
