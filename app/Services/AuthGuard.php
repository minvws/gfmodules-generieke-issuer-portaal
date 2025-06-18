<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Session\Session;

class AuthGuard implements Guard
{
    protected const SESSION_KEY = 'vc_user';

    public function __construct(
        protected Session $session,
        protected Dispatcher $events,
    ) {
    }

    public function check(): bool
    {
        return $this->session->has(self::SESSION_KEY);
    }

    public function guest(): bool
    {
        return !$this->check();
    }

    public function user(): User | null
    {
        if (!$this->check()) {
            return null;
        }

        return $this->session->get(self::SESSION_KEY);
    }

    public function id(): string | null
    {
        return $this->user()?->id;
    }

    /**
     * @param array<mixed> $credentials
     * @return mixed
     */
    public function validate(array $credentials = [])
    {
        throw new \RuntimeException('Not implemented AuthGuard::validate() method');
    }

    public function hasUser()
    {
        throw new \RuntimeException('Not implemented AuthGuard::hasUser() method');
    }

    public function setUser(Authenticatable $user): static
    {
        $this->session->put(self::SESSION_KEY, $user);
        $this->session->migrate(true);
        return $this;
    }

    /**
     * Logs out the current user.
     *
     * @return void
     */
    public function logout(): void
    {
        $user = $this->user();
        if (!$user) {
            return;
        }

        $this->clearUserDataFromStorage();

        $this->events->dispatch(new Logout('oidc', $user));
    }

    protected function clearUserDataFromStorage(): void
    {
        $this->session->forget(self::SESSION_KEY);
    }
}
