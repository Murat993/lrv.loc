<?php

namespace App\Http\Controllers\Cabinet;


use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\ProfileRequest;
use App\UseCases\Profile\ProfileService;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    private $service;

    public function __construct(ProfileService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $user = Auth::user();

        return view('cabinet.profile.home', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();

        return view('cabinet.profile.edit', compact('user'));
    }

    public function update(ProfileRequest $request)
    {
        try {
            $this->service->edit(Auth::id(), $request);
        } catch (\DomainException $e) {
                return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.profile.home');
    }
}
