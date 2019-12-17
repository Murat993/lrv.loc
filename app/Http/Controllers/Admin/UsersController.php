<?php

namespace App\Http\Controllers\Admin;

use App\Entity\User\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\CreateRequest;
use App\Http\Requests\Admin\Users\UpdateRequest;
use App\UseCases\Auth\RegisterService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{

    /**
     * @var RegisterService
     */
    private $registerService;

    public function __construct(RegisterService $registerService)
    {
        $this->registerService = $registerService;
        $this->middleware('can:manage-users');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $query = User::orderByDesc('id');

        if (!empty($value = $request->get('id'))) {
            $query->where('id', $value);
        }

        if (!empty($value = $request->get('name'))) {
            $query->where('name', 'like', '%' . $value . '%');
        }
        if (!empty($value = $request->get('email'))) {
            $query->where('email', 'like', '%' . $value . '%');
        }
        if (!empty($value = $request->get('status'))) {
            $query->where('status', $value);
        }
        if (!empty($value = $request->get('role'))) {
            $query->where('role', $value);
        }

        $users = $query->paginate(20);

        $statuses = User::USER_STATUSES;
        $roles = User::ROLE_STATUSES;

        return view('admin.users.index', compact('users', 'statuses', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(CreateRequest $request)
    {
        $user = User::new($request['name'], $request['email']);

        return redirect()->route('admin.users.show', ['id' => $user->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return Response
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return Response
     */
    public function edit(User $user)
    {
        $roles = User::ROLE_STATUSES;

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function update(UpdateRequest $request, User $user)
    {
        $user->update($request->only(['name', 'email']));

        if ($request['role'] !== $user->role) {
            $user->changeRole($request['role']);
        }

        return redirect()->route('admin.users.show', $user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index');
    }

    public function verify(User $user)
    {
        $this->registerService->verify($user->id);

        return redirect()->route('admin.users.show', $user);
    }
}
