<?php
namespace App\Services;

use App\Models\Structure;

class StructureService
{
    public function getAllChildStructures(Structure $structure)
    {
        $children = collect();

        foreach ($structure->children as $child) {
            $children->push($child);
            $children = $children->merge($this->getAllChildStructures($child));
        }

        return $children;
    }
}