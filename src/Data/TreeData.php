<?php

namespace SolutionForest\FilamentTree\Data;

use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Spatie\LaravelData\Data;

class TreeData extends Data
{
    public function __construct(
        public string $id,
        public string $type,
        public string $parent_id,
        public int $order,
        public string $title,
        public string $icon,
        public Collection $children,
        public string $url,
        public mixed $subTotal,
        public ?HtmlString $description = null,
    ) {}


}
