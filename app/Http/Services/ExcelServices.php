<?php

namespace App\Http\Services;

use App\Http\Repositories\UserRepositories;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\ImportUserRequest;

class ExcelServices{

    private $repo;

    public function __construct(UserRepositories $repo)
    {
        $this->repo = $repo;
    }
    public function import(ImportUserRequest $request)
    {
        try {
            $import = new UsersImport($this->repo);
            Excel::import($import, $request->file('file'));
            $failedRows = $import->getFailedRows();

            if ($failedRows) {
                return response()->json([
                    'success' => 'Users imported successfully!',
                    'failed' => $failedRows,
                ]);
            }
            return response()->json(['success' => 'Users imported successfully!']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error importing file: ' . $e->getMessage()]);
        }
    }
}

