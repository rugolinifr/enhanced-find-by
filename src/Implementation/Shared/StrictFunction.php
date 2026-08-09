<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Implementation\Shared;

use RuntimeException;

/**
 * Narrows the return types of some core PHP functions.
 */
class StrictFunction
{

    /**
     * @throws RuntimeException when the replacement fails.
     */
    public static function preg_replace(
        string $pattern,
        string $replacement,
        string $subject,
    ): string {
        $newString = preg_replace($pattern, $replacement, $subject);
        if (is_string($newString)) {
            return $newString;
        }
        throw new RuntimeException("preg_replace() functions failed !");
    }
}
