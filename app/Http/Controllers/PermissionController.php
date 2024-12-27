<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Requests\StorePermissionRequest;

class PermissionController extends Controller
{

    public function index()
    {
        $search = trim(request('search')) ?? '';
        $perPage = 15;
        if ($search === "") {
            $permissions = Permission::orderBy('id', 'desc')->paginate($perPage);
        } else {
            $permissions = Permission::search($search)
                ->orderBy('id', 'desc')
                ->paginate($perPage)
                ->appends([
                    'search' => $search,
                ]);
        }

        return view('permissions.index', ['permissions' => $permissions, 'search' => $search, 'perPage' => $perPage]);
    }

    public function create()
    {
        return view('permissions.create');
    }

    public function store(StorePermissionRequest $request)
    {
        $validated = $request->validated();

        $validated['name'] = ucfirst(strip_tags($validated['permission_name']));
        $validated['description'] = strip_tags(trim($validated['description']));

        Permission::create($validated);

        return redirect()->route('permissions.index')->with('success', 'Permission created successfully.');
    }

    public function edit(Permission $permission)
    {
        return view('permissions.edit', ['permission' => $permission]);
    }

    public function update(StorePermissionRequest $request, Permission $permission)
    {
        $validated = $request->validated();

        $permission->permission_name = ucfirst(strip_tags($validated['permission_name']));
        $permission->description = strip_tags(trim($validated['description']));
        $permission->save();

        return redirect()->route('permissions.edit', ['permission' => $permission->id])->with('success', 'Permission updated successfully.');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully');
    }
}
