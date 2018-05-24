<?php

namespace Makeable\LaravelInvoicing;

use Illuminate\Database\Eloquent\Relations\Relation;

trait InteractsWithMorphs
{
    /**
     * @param $class
     * @return false|int|string
     */
    protected function getMorphClassFor($class)
    {
        $morphMap = Relation::morphMap();

        if (! empty($morphMap) && in_array($class, $morphMap)) {
            return array_search($class, $morphMap, true);
        }

        return $class;
    }
}
