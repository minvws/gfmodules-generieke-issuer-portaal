<?php

declare(strict_types=1);

namespace App\Http\Responses;

use App\Exceptions\UziNoUziNumberException;
use App\Models\UziUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use MinVWS\OpenIDConnectLaravel\Http\Responses\LoginResponseHandlerInterface;
use Symfony\Component\HttpFoundation\Response;

class OidcLoginResponseHandler implements LoginResponseHandlerInterface
{
    /**
     * @param object{
     *      relations: array<int, object{entity_name: string, ura: string, roles: string[]}>,
     *      initials: ?string,
     *      surname: ?string,
     *      surname_prefix: ?string,
     *      uzi_id: string,
     *      loa_uzi: string,
     *      loa_authn: string
     *  } $userInfo
     * @throws UziNoUziNumberException
     */
    public function handleLoginResponse(object $userInfo): Response
    {
        $user = UziUser::deserializeFromObject($userInfo);
        if ($user === null) {
            return redirect()
                ->route('index')
                ->with('error', __('Something went wrong with logging in, please try again.'));
        }
        if (!$user->hasUziId()) {
            throw new UziNoUziNumberException();
        }

        Auth::setUser($user);
        return new RedirectResponse(route('flow'));
    }
}
