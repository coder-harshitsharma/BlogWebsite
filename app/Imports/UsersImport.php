<?php

namespace App\Imports;

use App\Http\Repositories\UserRepositories;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    private $repo;

    public function __construct(UserRepositories $repo){
        $this->repo = $repo;
    }
    
    public function model(array $row)
    {
        if (!isset($row[0], $row[1], $row[2])) {
            return null;
        }

        return $this->repo->userimport($row);
    }

    public function getFailedRows()
    {
        return $this->repo->getFailedRows();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ];
    }

}
