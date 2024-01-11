<?php

namespace App\Modules\Token\Traits;

trait KeyRenaming
{
    /**
     * @param array $data
     * @param string $prefix
     * @return array
     */
    public function renameKeysWithPrefix(array $data, string $prefix): array
    {
        return collect($data)->map(function ($value, $key) use ($prefix) {
            return [$this->renameKeyWithPrefix($key, $prefix) => $value];
        })->collapse()->toArray();
    }

    /**
     * @param string $key
     * @param string $prefix
     * @return string
     */
    private function renameKeyWithPrefix(string $key, string $prefix): string
    {
        if (str_starts_with($key, $prefix)) {
            return substr($key, strlen($prefix));
        }
        return $key;
    }
}
