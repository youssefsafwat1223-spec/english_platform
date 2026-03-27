<?php

namespace App\Models\Concerns;

use App\Support\TextEncoding;

trait RepairsMojibakeAttributes
{
    public function getAttributeValue($key): mixed
    {
        $value = parent::getAttributeValue($key);

        if (is_string($value) && in_array($key, $this->repairableTextAttributes(), true)) {
            return TextEncoding::repair($value);
        }

        return $value;
    }

    protected function repairableTextAttributes(): array
    {
        return property_exists($this, 'repairableTextAttributes')
            ? $this->repairableTextAttributes
            : [];
    }
}
