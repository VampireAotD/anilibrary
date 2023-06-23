<?php

declare(strict_types=1);

namespace App\Telegram\Callbacks\Traits;

use Exception;

/**
 * Trait CanSafelyReceiveCallbackArgumentsTrait
 * @package App\Telegram\Callbacks\Traits
 */
trait CanSafelyReceiveCallbackArgumentsTrait
{
    protected function safelyRetrieveArguments(): array
    {
        try {
            return $this->arguments();
        } catch (Exception) {
            return [];
        }
    }
}
