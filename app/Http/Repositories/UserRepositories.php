<?php
namespace App\Http\Repositories;
use App\Models\User;
use App\Http\Requests\UserRequest;

class UserRepositories {
    private $model;
    protected $failedRows = [];
    public function __construct(User $model){
        $this->model = $model;
    }

    public function getusers() {
        return $this->model->all();
    }
    public function RegisterUser(UserRequest $request)
    {
        $validated = $request->validated();
        $imagePath = $request->hasFile('image') ? $request->file('image')->store('images', 'public') : null;
        return $this->model->create([
            'image_path' => $imagePath,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password']
        ]);
    }

    public function getUser(int $id){
        return $this->model->with('posts')->find($id);
    }

    public function edituser(int $id)
    {
        return $this->model->find($id);
    }

    public function updatepost(UserRequest $request, int $id)
    {
        $user = $this->model->find($id);
        if ($user) {
            $validated = $request->validated();
            $imagePath = $request->hasFile('image') ? $request->file('image')->store('images', 'public') : $user->image_path;

            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'image_path' => $imagePath
            ]);

            return true;
        }
        return false;
    }

    public function userimport(array $row){
        if (User::where('email', $row[1])->exists()) {
            $this->failedRows[] = [
                'name' => $row[0],
                'email' => $row[1],
                'reason' => 'User already exists',
            ];
            return null;
        }

        return new User([
            'name' => $row[0],
            'email' => $row[1],
            'password' => bcrypt($row[2]),
        ]);
    }

    public function getFailedRows()
    {
        return $this->failedRows;
    }

}



