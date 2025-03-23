<?php

declare(strict_types=1);

namespace LM\WebFramework\Session;

final class SessionManager
{
    public const CSRF = 'csrf';

    public const CSRF_N_BYTES = 32;

    public const CURRENT_USERNAME_KEY = "cmu";

    public const CUSTOM_PREFIX = 'custom_';

    public const MESSAGES = 'messages';

    public function getCsrf(): string
    {
        return $_SESSION[self::CSRF] ?? $_SESSION[self::CSRF] = bin2hex(random_bytes(self::CSRF_N_BYTES));
    }

    public function getCurrentUsername(): ?string
    {
        if ($this->isUserLoggedIn()) {
            return $_SESSION[self::CURRENT_USERNAME_KEY];
        } else {
            return null;
        }
    }

    public function getCustom(string $key): string
    {
        return $_SESSION[self::CUSTOM_PREFIX . $key];
    }

    public function isUserLoggedIn(): bool
    {
        return key_exists(self::CURRENT_USERNAME_KEY, $_SESSION) && null !== $_SESSION[self::CURRENT_USERNAME_KEY];
    }

    /**
     * @todo Should not accept null.
     */
    public function setCurrentUsername(?string $username): void
    {
        $_SESSION[self::CURRENT_USERNAME_KEY] = $username;
    }

    public function setCustom(string $key, string $value): void
    {
        $_SESSION[self::CUSTOM_PREFIX . $key] = $value;
    }

    public function addMessage(string $message): void
    {
        if (key_exists(self::MESSAGES, $_SESSION)) {
            $_SESSION[self::MESSAGES][] = $message;
        } else {
            $_SESSION[self::MESSAGES] = [
                $message,
            ];
        }
    }

    public function getAndDeleteMessages(): array
    {
        $messages = $_SESSION[self::MESSAGES] ?? [];
        $_SESSION[self::MESSAGES] = [];
        return $messages;
    }
}
