<?php

declare(strict_types=1);

namespace Ssc\Btlr\App\JsonPath;

/**
 * Partly raided from igorw/get-in, because Igor is a genius.
 *
 * This implementation pretends to follow the JSON Path specification,
 * with the following few elements being supported:
 *
 * * $path needs to start with `$`
 * * each key in the path needs to be separated by a `.`
 * * `*[<number>]` will return the key-value pair, at "number"'s position
 */
class Get
{
    /** @param mixed[] $parameters */
    public function in(array $parameters, string $path, mixed $default = null): mixed
    {
        if (false === str_starts_with($path, '$.')) {
            throw FailedDueTo\Path\MissingStartingDollar::with($path);
        }
        $keys = explode('.', $path);
        array_shift($keys); // Can ignore first item, which would be "$" denoting the root of the parameters

        if ([] === $keys) {
            return $parameters;
        }

        // This is a micro-optimization, it is fast for non-nested keys, but fails for null values
        if (1 === count($keys) && isset($parameters[$keys[0]])) {
            return $parameters[$keys[0]];
        }

        $current = $parameters;
        foreach ($keys as $key) {
            if (true === is_array($current) && true === array_key_exists($key, $current)) {
                $current = $current[$key];

                continue;
            }
            if (1 === preg_match('/\*\[(\d+)\]$/', $key, $matches)) {
                $position = (int) $matches[1];
                $i = 0;
                foreach ($current as $subKey => $value) {
                    if ($position === $i++) {
                        return [$subKey => $value];
                    }
                }
            }

            return $default;
        }

        return $current;
    }
}
