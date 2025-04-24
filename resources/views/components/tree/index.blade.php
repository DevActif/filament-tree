@php
    $containerKey = 'filament_tree_container_' . $this->getId();
    $maxDepth = $getMaxDepth() ?? 1;
    $records = collect($this->getRootLayerRecords() ?? []);

@endphp

<div wire:disabled="updateTree" x-ignore ax-load
    ax-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('filament-tree-component', 'solution-forest/filament-tree') }}"
    x-data="treeNestableComponent({
        containerKey: {{ $containerKey }},
        maxDepth: {{ $maxDepth }}
    })">
    <x-filament::section :heading="$this->displayTreeTitle() ?? false ? $this->getTreeTitle() : null">
        <menu class="flex gap-2 mb-4" id="nestable-menu">
            <div class="btn-group">
                <x-filament::button color="gray" tag="button" data-action="expand-all"
                    x-on:click="expandAll()" wire:loading.attr="disabled"
                    wire:loading.class="cursor-wait opacity-70">
                    {{ __('filament-tree::filament-tree.button.expand_all') }}
                </x-filament::button>
                <x-filament::button color="gray" tag="button" data-action="collapse-all"
                    x-on:click="collapseAll()" wire:loading.attr="disabled"
                    wire:loading.class="cursor-wait opacity-70">
                    {{ __('filament-tree::filament-tree.button.collapse_all') }}
                </x-filament::button>
            </div>
        </menu>
        <div class="filament-tree dd" id="{{ $containerKey }}">
            <x-filament-tree::tree.list :records="$records" :containerKey="$containerKey" :tree="$tree" />
        </div>
    </x-filament::section>
</div>
