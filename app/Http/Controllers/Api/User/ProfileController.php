<?php

namespace App\Http\Controllers\Api\User;

use App\Entity\User\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\ProfileRequest;
use App\Http\Serializers\UserSerializer;
use App\UseCases\Profile\ProfileService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    private $service;
    private $serializer;

    public function __construct(ProfileService $service, UserSerializer $serializer)
    {
        $this->service = $service;
        $this->serializer = $serializer;
    }

    /**
     * @SWG\Get(
     *     path="/user",
     *     tags={"Profile"},
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *         @SWG\Schema(ref="#/definitions/Profile"),
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     */
    public function show(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        return $this->serializer->profile($user);
    }

    /**
     * @SWG\Put(
     *     path="/user",
     *     tags={"Profile"},
     *     @SWG\Parameter(name="body", in="body", required=true, @SWG\Schema(ref="#/definitions/ProfileRequest")),
     *     @SWG\Response(
     *         response=200,
     *         description="Success response",
     *     ),
     *     security={{"Bearer": {}, "OAuth2": {}}}
     * )
     */
    public function update(ProfileRequest $request)
    {
        $this->service->edit($request->user()->id, $request);
        $user = User::findOrFail($request->user()->id);
        return $this->serializer->profile($user);
    }
}
