<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;
use Symfony\Component\HttpFoundation\Response;

class FortifyTwoFactorLoginResponse implements TwoFactorLoginResponseContract
{
    public function toResponse($request): Response
    {
        if ($request->wantsJson()) {
            return new JsonResponse('', 204);
        }

        return redirect()->intended($this->homePath($request));
    }

    private function homePath(Request $request): string
    {
        return $request->user()?->can('admin')
            ? route('admin.dashboard')
            : route('dashboard');
    }
}
