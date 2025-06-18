<?php

declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use MinVWS\OpenIDConnectLaravel\Http\Responses\LoginResponseHandlerInterface;
use Symfony\Component\HttpFoundation\Response;

class OidcLoginResponseHandler implements LoginResponseHandlerInterface
{
    /**
     * @param object{
    *     id: string,
    *     organization_code: string,
     *  } $userInfo
     */
    public function handleLoginResponse(object $userInfo): Response
    {
        $user = User::deserializeFromObject($userInfo);
        if ($user === null) {
            return redirect()
                ->route('index')
                ->with('error', __('Something went wrong with logging in, please try again.'));
        }
        if (!$user->getName()) {
            throw new Exception('User does not have a valid name.');
        }

        Auth::setUser($user);

        return new RedirectResponse(route('flow'));
    }
}
