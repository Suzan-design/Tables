<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use Spatie\Permission\Models\Permission;
use DB;
class RoleController extends Controller
{
    protected $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }
    public function index(Request $request)
    {
        $permission = Permission::get();
        $roles = $this->roleRepository->getAllRoles();
        return view('Admin.Roles.index', compact('roles', 'permission'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }
    public function store(Request $request)
    {
        $data = $request->only(['name', 'permissions']);
        $this->roleRepository->createRole($data);
        return redirect()->route('roles.index')->with('success', 'Role created successfully');
    }
    public function edit(Request $request)
    {
    }
    public function show($id)
    {
        $role = $this->roleRepository->findRoleById($id);
        $rolePermissions = $role->permissions;
        return view('Admin.Roles.show', compact('role', 'rolePermissions'));
    }
    public function update(Request $request, $id)
    {
        $data = $request->only(['name', 'permissions']);
        $this->roleRepository->updateRole($id, $data);
        return redirect()->route('roles.index')->with('success', 'Role updated successfully');
    }
    public function destroy($id)
    {
        $this->roleRepository->deleteRole($id);
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully');
    }
}
