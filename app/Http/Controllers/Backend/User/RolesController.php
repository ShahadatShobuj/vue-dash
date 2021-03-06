<?php

namespace App\Http\Controllers\Backend\User;

use Illuminate\Http\Request;
use App\Models\Backend\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use App\Models\Backend\User\Status;
use Yajra\DataTables\Facades\DataTables;

use App\Http\Controllers\Controller;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.users.roles');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:45|unique:roles',
        ]);

        $newRole = Role::create($request->all());
        if($newRole)
        {
            return ['status' => 'Success', 'message' => 'New role created successfully.'];
        } else {

            return ['status' => 'warning', 'messsage' => 'Something went wrong!'];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function dataTable()
    {
        $outputs = DB::table('roles')->select(['id', 'name']);

        return DataTables::of($outputs)
            ->addColumn('delete', function($query){
                return '<button  data-id="'.$query->id.'" class="btn btn-sm btn-danger delete"><i class="fa fa-trash"></i> Delete</button>';
            })
            ->addColumn('edit',function ($query) {
                return '<a  href="" data-id="'.$query->id.'" data-name="'.$query->name.'" class="btn btn-sm btn-info edit"><i class="fa fa-edit"></i> Edit</a>';
            })
           ->rawColumns(['edit', 'delete'])
            ->make(true);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191|unique:roles,name,'.$request->id,
        ]);

        if($role->update($request->all()))
        {
            return ['status' => 'Success', 'message' => 'Role deleted successfully.'];
        } else {
            return ['status' => 'warning', 'messsage' => 'Something went wrong!'];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        try {
            $role->delete();
            return ['status' => 'Success', 'message' => 'Role deleted successfully.'];
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function roles()
    {
        return Role::all();
    }

    public function assignRole(Request $request)
    {
        $this->validate($request, [
                'user' => 'required',
                'role' => 'required',
            ]
        );

        $user= User::find($request->user);
        if($user->syncRoles($request->role))
        {
            $this->refreshApp();
            return ['status' => 'Success', 'message' => 'New role assigned successfully.'];
        } else {

            return ['status' => 'warning', 'messsage' => 'Something went wrong!'];
        }
    }

    public function refreshApp()
    {
        $configClear = Artisan::call('config:clear');
        $cacheClear = Artisan::call('cache:clear');
        $routeClear = Artisan::call('route:cache');
        // $viewClear = Artisan::call('view:cache');
        return true; //Return anything
    }
}
