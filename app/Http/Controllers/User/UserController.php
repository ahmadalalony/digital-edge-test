<?php

namespace App\Http\Controllers\User;

use App\DTOs\User\AdminChangePasswordDTO;
use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\User\AdminChangePasswordRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Services\User\UserService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;


class UserController extends Controller
{
    use ApiResponse;

    public function __construct(private UserService $userService)
    {
    }

    public function index(Request $request)
    {
        // Check if this is a DataTables request
        if ($request->has('draw')) {
            $search = $request->input('search.value');
            $length = (int) $request->input('length', 10);
            $start = (int) $request->input('start', 0);
            $page = (int) floor($start / max($length, 1)) + 1;

            // Make Laravel paginator use DataTables page
            $request->merge(['page' => $page]);

            // Support filters by country/city - Get from direct parameters or columns
            $filters = [
                'country' => $request->input('country') ?? $request->input('columns.5.search.value', ''),
                'city' => $request->input('city') ?? $request->input('columns.6.search.value', ''),
            ];

            $users = $this->userService->list($length, $search, $filters);

            return response()->json([
                'draw' => intval($request->input('draw')),
                'recordsTotal' => $users->total(),
                'recordsFiltered' => $users->total(),
                'data' => UserResource::collection($users->items())->resolve(),
            ]);
        }

        $users = $this->userService->list();

        return $this->successResponse(UserResource::collection($users), 'Users retrieved successfully');
    }

    public function store(StoreUserRequest $request)
    {
        try {
            $validated = $request->validated();

            $validated['password'] = Hash::make($validated['password']);
            $result = $this->userService->create($validated);
            if (!$result['success']) {
                throw new \Exception($result['error'] ?? 'User creation failed');
            }
            $user = $result['user'];

            // Log successful user creation
            Log::info('User created successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'created_by' => Auth::id()
            ]);

            return redirect()->route('admin_users_index')->with('success', __('dashboard.User created successfully'));

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log validation errors
            Log::warning('User creation validation failed', [
                'errors' => $e->errors(),
                'input' => $request->except('password', 'password_confirmation'),
                'user_id' => Auth::id()
            ]);

            return redirect()->back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            // Log unexpected errors
            Log::error('User creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->except('password', 'password_confirmation'),
                'user_id' => Auth::id()
            ]);

            return redirect()->back()->withErrors(['error' => __('dashboard.Error occurred while creating user')])->withInput();
        }
    }

    public function destroy(int $id)
    {
        $result = $this->userService->destroy($id);

        if ($result['success']) {
            return $this->successResponse([], 'User deleted successfully');
        }
        return $this->errorResponse('User deletion failed', 400, $result['error']);
    }

    public function export(Request $request)
    {
        // For web routes, we don't need token authentication
        return Excel::download(new UsersExport($request->all()), 'users.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function changePassword(ChangePasswordRequest $request, int $id)
    {
        $result = $this->userService->adminChangePassword(AdminChangePasswordDTO::fromArray([
            'user_id' => $id,
            'new_password' => $request->validated()['new_password'],
            'admin_id' => Auth::id()
        ]));
        if (!$result['success']) {
            return redirect()->back()->withErrors(['error' => $result['error'] ?? __('dashboard.Error occurred while changing password')]);
        }

        return redirect()->back()->with('success', __('dashboard.Save Changes'));
    }

    public function adminChangePassword(AdminChangePasswordRequest $request, int $id)
    {
        try {
            $dto = AdminChangePasswordDTO::fromArray([
                'user_id' => $id,
                'new_password' => $request->validated()['new_password'],
                'admin_id' => Auth::id()
            ]);

            $result = $this->userService->adminChangePassword($dto);

            if ($result['success']) {
                return redirect()->back()->with('success', __('dashboard.Password changed successfully'));
            }

            return redirect()->back()->withErrors(['error' => $result['error']]);

        } catch (\Exception $e) {
            Log::error('Admin password change request failed', [
                'error' => $e->getMessage(),
                'user_id' => $id,
                'admin_id' => Auth::id()
            ]);

            return redirect()->back()->withErrors(['error' => __('dashboard.Error occurred while changing password')]);
        }
    }
}
