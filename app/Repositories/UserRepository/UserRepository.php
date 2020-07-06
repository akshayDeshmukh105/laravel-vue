<?php


namespace App\Repositories\UserRepository;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use App;


class UserRepository implements UserRepositoryInterface
{
    /**
     * @var array
     * used for sorting in users datatable
     */
    private $userSortingIndex = [
        'users.id',
        'users.name',
        'users.email',
    ];

    public function authenticate($email, $password, $rememberMe = true)
    {
        if (Auth::attempt(['email' => $email, 'password' => $password], $rememberMe)) {
            return Auth::user();
        } else {
            return null;
        }
    }

    /**
     * @param string $email
     * @param int $userId
     * @return mixed
     */
    public function checkUserExist($email = '', $userId = 0)
    {
        return App\User::select('*')
            ->when(strlen($email), function ($query) use ($email) {
                return $query->where('email', $email);
            })
            ->when($userId, function ($query) use ($userId) {
                return $query->where('id', '!=', $userId);
            })
            ->first();
    }

    /**
     * @param array $params
     * @return mixed
     *
     * Get all users
     */
    public function getAllUsers($params = array())
    {
        extract($params);
        $query = App\User::select('*');

        if (isset($search)) {
            $query->where(function ($query) use ($search) {
                return $query->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }

        if (isset($userId) && $userId) {
            $query->where('users.id', $userId);
        }

        if (isset($authUserId)) {
            $query->where('users.id', '!=', $authUserId);
        }

        if (isset($sortByIndex) && isset($sortBy)) {
            $query->orderBy($this->userSortingIndex[$sortByIndex], $sortBy);
        }

        if (isset($limit) && $limit) {
            if (isset($offset)) {
                $query->limit($limit)->offset($offset);
                return $query->paginate($limit, ['*'], 'offset', $offset)->toArray();
            } elseif (isset($page)) {
                return $query->paginate($limit, ['*'], 'page', $page)->toArray();
            } else {
                return $query->paginate($limit, ['*'], 'offset', 0)->toArray();
            }
        } else {
            $users = $query->get();
            return $users;
        }
    }

    /**
     * @param $request
     * @return array
     *
     * Create user
     */
    public function createUser(Request $request)
    {
        $response = array();
        $name = $request->name;
        $email = $request->email;
        $password = $request->password;

        $userId = App\User::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
        ])->id;

        if ($userId) {
            $response['status'] = true;
            $response['message'] = Lang::get('custom.action_success', ['name' => 'User', 'action' => 'created']);
        } else {
            $response['status'] = false;
            $response['message'] = Lang::get('custom.something_wrong');
        }
        return $response;
    }

    /**
     * @param $userDetail
     * @param $request
     * @return array
     *
     * Update user
     */
    public function updateUser($userId, Request $request)
    {
        $response = array();
        $name = $request->name;
        $email = $request->email;
        $password = $request->password ? $request->password : '';
        $userDetail = App\User::find($userId);

        if ($userDetail) {
            $userDetail->name = $name;
            $userDetail->email = $email;
            if ($password) {
                $userDetail->password = bcrypt($password);
            }
            $userDetail->save();
            $response['status'] = true;
            $response['message'] = Lang::get('custom.action_success', ['name' => 'User', 'action' => 'updated']);
        } else {
            $response['status'] = false;
            $response['message'] = Lang::get('custom.something_wrong');
        }
        return $response;
    }

    /**
     * @param $request
     * @param $userId
     * @return array
     *
     * Delete user
     */
    public function deleteUser($userId)
    {
        $response = array();
        $userDetail = App\User::find($userId);
        if ($userDetail) {
            $userDetail->delete();
            $response['status'] = true;
            $response['message'] = Lang::get('custom.action_success', ['name' => 'User', 'action' => 'deleted']);
        } else {
            $response['status'] = false;
            $response['message'] = Lang::get('custom.not_found', ['name' => 'User']);
        }
        return $response;
    }
}
