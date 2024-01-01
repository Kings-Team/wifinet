<?php

namespace App\Services;

use App\Exceptions\WebException;
use App\Models\Mitra;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserServices
{

    private User $user;
    private Role $role;
    private Mitra $mitra;
    private Permission $permission;

    public function __construct()
    {
        $this->user = new User();
        $this->mitra = new Mitra();
        $this->role = new Role();
        $this->permission = new Permission();
    }

    public function fetchAllUser()
    {
        $data = $this->user->with(['mitra', 'roles', 'roles.permissions'])->orderByDesc('id')
            ->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'route');
            })
            ->get();
        $user = $this->user->paginate(10);
        $mitra = $this->mitra->all();
        $role = $this->role->with('permissions');
        $permission = $this->permission->all();

        return compact('data', 'user', 'mitra', 'permission', 'role');
    }

    public function addUser($request)
    {
        DB::beginTransaction();

        try {
            $isCreated = $this->user->create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => bcrypt($request['password']),
                'mitra_id' => $request['mitra_id'],
            ]);

            if ($isCreated) {
                $mitra = Mitra::find($request['mitra_id']);
                if (!isset($mitra)) {
                    throw new WebException('Data mitra tidak ditemukan');
                }

                $namaMitra = $mitra->nama_mitra;

                $roleName = $request['name_role'] . ' ' . $namaMitra;

                $role = $this->role->create(['name' => $roleName, 'guard_name' => 'web']);

                $isCreated->assignRole($role);

                $role->syncPermissions($request['permissions'] ?? []);

                DB::commit();
                return [
                    'status' => true,
                    'code' => 201,
                    'message' => "Berhasil menambah user",
                ];
            }

            throw new WebException('Gagal menambah data user');
        } catch (\Exception $e) {
            DB::rollBack();
            throw new WebException($e->getMessage());
        }
    }

    public function updateUser($request, $id)
    {
        try {
            $user = $this->user->findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new WebException($e->getMessage());
        }

        DB::beginTransaction();

        if (isset($user)) {
            try {
                $update = $user->update([
                    'name' => $request['name'],
                    'email' => $request['email'],
                    'mitra_id' => $request['mitra_id'],
                ]);

                if ($update) {
                    $mitra = Mitra::find($request['mitra_id']);

                    if (!isset($mitra)) {
                        throw new WebException('Data mitra tidak ditemukan');
                    }

                    $namaMitra = $mitra->nama_mitra;

                    $roleName = $request['name_role'] . ' ' . $namaMitra;

                    $existingRole = $this->role->where(['name' => $roleName])->first();

                    if (!$existingRole) {
                        $role = $this->role->create(['name' => $roleName, 'guard_name' => 'web']);
                    } else {
                        $role = $existingRole;
                    }

                    $user->syncRoles([$role]);

                    $role->syncPermissions($request['permissions'] ?? []);

                    if ($request['password']) {
                        $user->update(['password' => bcrypt($request['password'])]);
                    }

                    DB::commit();

                    return [
                        'status' => true,
                        'code' => 200,
                        'message' => "Berhasil mengupdate user",
                    ];
                }

                throw new WebException('Gagal mengupdate data user');
            } catch (\Throwable $th) {
                throw new WebException($th->getMessage());
            }
        }

        throw new WebException('Gagal mengupdate user, user tidak ditemukan');
    }


    public function deleteUser($id)
    {
        try {
            $user = $this->user->findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new WebException($e->getMessage());
        }

        DB::beginTransaction();
        if (isset($user)) {
            $delete = $user->delete();
            if ($delete) {
                DB::commit();
                return back()->with('message', 'Berhasil menghapus user');
            }
            throw new WebException('Gagal menghapus user, terjadi kesalahan');
        }
        throw new WebException('Gagal menghapus user, user tidak ditemukan');
    }
}
