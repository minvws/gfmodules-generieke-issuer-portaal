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
     *  } $userInfo
     */
    public function handleLoginResponse(object $userInfo): Response
    {
        try {
            $jsonDecoded = json_encode($userInfo, JSON_THROW_ON_ERROR, 512);
            $user = new User(
                $jsonDecoded
            );
        } catch (Exception $e) {
            return redirect()
                ->route('index')
                ->with('error', __('Something went wrong with logging in, please try again.'))
                ->with('error_description', $e->getMessage());
        }

        Auth::setUser($user);

        return new RedirectResponse(route('flow'));
    }
}
