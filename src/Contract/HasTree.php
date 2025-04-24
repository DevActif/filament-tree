<?php

namespace SolutionForest\FilamentTree\Contract;

use SolutionForest\FilamentTree\Data\TreeData;
use SolutionForest\FilamentTree\Components\Tree;

interface HasTree
{
    public static function tree(Tree $tree): Tree;

    public function getTreeRecordTitle(?TreeData $record = null): string;

    public function getRecordKey(?TreeData $record): ?string;
}
