<?php

namespace App\Http\Controllers\Dashboard;

use App\DTOs\User\SendEmailDTO;
use App\DTOs\User\UpdateUserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\SendEmailRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Services\User\UserService;

class UserViewController extends Controller
{
    public function __construct(private UserService $userService)
    {
    }

    public function index()
    {
        return view('dashboard.users.index');
    }

    public function create()
    {
        return view('dashboard.users.create');
    }

    public function edit($id)
    {
        $result = $this->userService->findWithRelations((int) $id, ['assignedProducts']);
        if (!$result['success']) {
            abort(404);
        }
        $user = $result['user'];
        return view('dashboard.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $dto = UpdateUserDTO::fromArray(array_merge($request->validated(), ['id' => (int) $id]));
        $result = $this->userService->update($dto);

        if ($result['success']) {
            return redirect()->back()->with('success', __('users.updated_successfully'));
        }

        return redirect()->back()->withErrors(['error' => $result['error'] ?? __('dashboard.Error occurred')]);
    }

    public function emailForm($id)
    {
        $result = $this->userService->show((int) $id);
        if (!$result['success']) {
            abort(404);
        }
        $user = $result['user'];
        return view('dashboard.users.email', compact('user'));
    }

    public function sendEmail(SendEmailRequest $request, $id)
    {
        $result = $this->userService->show((int) $id);
        if (!$result['success']) {
            abort(404);
        }
        $user = $result['user'];
        $dto = SendEmailDTO::fromArray([
            'user' => $user,
            'subject' => $request->validated()['subject'],
            'message' => $request->validated()['message'],
        ]);
        $this->userService->sendCustomMail($dto);

        return redirect()->back()->with('success', __('users.email_sent_successfully'));
    }
}
