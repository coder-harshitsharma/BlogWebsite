<?php

namespace App\Http\Repositories;

use App\Models\User;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class AdminRepositories
{
    private $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        return $this->model->all();
    }

    public function getusers(Request $request)
    {
        if ($request->ajax()) {
            $users = $this->model->latest()->get();
            return Datatables::of($users)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = ' <a data-id="' . $row->id . '" class="delete btn btn-danger btn-sm" id="deletebtn">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function destroyuser(int $id)
    {
        $user = $this->model->find($id);
        $user->delete();
        return response()->json(['success' => 'User deleted successfully.']);
    }
}



