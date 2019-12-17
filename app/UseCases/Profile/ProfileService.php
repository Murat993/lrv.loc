<?php

namespace App\UseCases\Profile;

use App\Entity\User\User;
use App\Http\Requests\Cabinet\ProfileRequest;


class ProfileService
{
    public function edit($id, ProfileRequest $request): void
    {
        /** @var User $user */
        $user = User::findOrFail($id);
        $oldPhone = $user->phone;

        $user->update($request->only('name', 'last_name', 'phone'));

        if ($user->phone !== $oldPhone) {
            $user->unverifyPhone();
        }
    }
}
