<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Profile;
use App\Models\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class Statistical extends Controller
{
    /**
     * 8.1. Lấy danh sách người dùng
     * GET /api/admin/users
     */
    public function getUsers(Request $request)
    {
        $page = $request->query('page', 1);
        $limit = $request->query('limit', 10);
        $search = $request->query('search');
        $status = $request->query('status');
        $role = $request->query('role');

        // Validate parameters
        $request->validate([
            'page' => 'integer|min:1',
            'limit' => 'integer|min:1|max:100',
            'status' => 'in:active,inactive',
            'role' => 'exists:rule,id',
        ]);

        // Base query
        $query = Account::with('profile')
            ->select('account.id', 'account.username', 'account.rule', 'account.status', 'account.created_at')
            ->when($search, function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhereHas('profile', function ($q) use ($search) {
                      $q->where('fullname', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            })
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->when($role, function ($q) use ($role) {
                $q->where('rule', $role);
            });

        // Paginate
        $users = $query->paginate($limit, ['*'], 'page', $page);

        return response()->json([
            'status' => 'success',
            'data' => $users->items(),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'total_pages' => $users->lastPage(),
                'total' => $users->total(),
            ],
        ]);
    }

    /**
     * 8.2. Thêm người dùng mới
     * POST /api/admin/users
     */
    public function createUser(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'username' => 'required|unique:account,username|max:255',
            'password' => 'required|min:6',
            'fullname' => 'required|max:255',
            'phone_number' => 'required|max:10',
            'email' => 'required|email|unique:profile,email|max:255',
            'rule' => 'required|exists:rule,id',
            'status' => 'required|in:active,inactive',
        ]);

        // Create account
        $account = Account::create([
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'rule' => $validated['rule'],
            'status' => $validated['status'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create profile
        Profile::create([
            'account_id' => $account->id,
            'fullname' => $validated['fullname'],
            'phone_number' => $validated['phone_number'],
            'email' => $validated['email'],
            'avatar' => null, // Default or upload logic
        ]);

        // Return response
        $user = Account::with('profile')->find($account->id);
        return response()->json([
            'status' => 'success',
            'data' => $user,
        ], 201);
    }

    /**
     * 8.3. Lấy chi tiết người dùng
     * GET /api/admin/users/:id
     */
    public function getUser($id)
    {
        $user = Account::with('profile')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $user,
        ]);
    }

    /**
     * 8.4. Cập nhật thông tin người dùng
     * PUT /api/admin/users/:id
     */
    public function updateUser(Request $request, $id)
    {
        $account = Account::findOrFail($id);

        // Validate request
        $validated = $request->validate([
            'username' => ['required', 'max:255', Rule::unique('account', 'username')->ignore($id)],
            'password' => 'nullable|min:6',
            'fullname' => 'required|max:255',
            'phone_number' => 'required|max:10',
            'email' => ['required', 'email', 'max:255', Rule::unique('profile', 'email')->where(function ($query) use ($id) {
                return $query->where('account_id', '!=', $id);
            })],
            'rule' => 'required|exists:rule,id',
            'status' => 'required|in:active,inactive',
        ]);

        // Update account
        $account->update([
            'username' => $validated['username'],
            'password' => $validated['password'] ? Hash::make($validated['password']) : $account->password,
            'rule' => $validated['rule'],
            'status' => $validated['status'],
            'updated_at' => now(),
        ]);

        // Update profile
        $profile = Profile::where('account_id', $id)->firstOrFail();
        $profile->update([
            'fullname' => $validated['fullname'],
            'phone_number' => $validated['phone_number'],
            'email' => $validated['email'],
        ]);

        // Return response
        $user = Account::with('profile')->find($id);
        return response()->json([
            'status' => 'success',
            'data' => $user,
        ]);
    }

    /**
     * 8.5. Xóa người dùng
     * DELETE /api/admin/users/:id
     */
    public function deleteUser($id)
    {
        $account = Account::findOrFail($id);
        $account->delete();
        Profile::where('account_id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Người dùng đã được xóa thành công',
        ]);
    }

}