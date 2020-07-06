<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Repositories\UserRepository\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected $userRepository;

    function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * Authenticate User
     */
    public function login(Request $request)
    {
        $response = [];
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'password.required' => 'Please enter password',
            'email.required' => 'Please enter email address',
            'email.email' => 'Please enter valid email address'
        ]);

        if ($validator->fails()) {
            $response['status'] = false;
            $response['errors'] = $validator->errors();
            $response['message'] = Lang::get('custom.invalid_input');
            return Response::json($response);
        }

        try {
            $email = trim($request->email);
            $password = trim($request->password);
            if (isset($request->remember_me) && $request->remember_me == '1') {
                $rememberMe = true;
            } else {
                $rememberMe = false;
            }
            $user = $this->userRepository->authenticate($email, $password, $rememberMe);
            if ($user) {
                $response['status'] = true;
                $response['user'] = $user;
                $response['token'] = $user->createToken('Authorize Token')->accessToken;
            } else {
                $response['status'] = false;
                $response['message'] = Lang::get('auth.failed');
            }
        } catch (\Exception $exception) {
            Log::error(__CLASS__ . "::" . __METHOD__ . ' : ' . $exception->getMessage());
            $response = [];
            $response['status'] = false;
            $response['message'] = Lang::get('custom.something_wrong');
        }

        return Response::json($response);
    }

    public function getUsers(Request $request)
    {
        $response = [];
        try {
            $user = Auth::user();
            $limits = $request->only('page', 'limit');
            $page = isset($limits['page']) ? intval($limits['page']) : 0;
            $limit = isset($limits['limit']) ? ($limits['limit']) : 0;
            $id = isset($request['id']) ? $request['id'] : 0;

            $params = [];
            $params['authUserId'] = $user->id;
            $params['userId'] = $id;
            $params['limit'] = $limit;
            $params['page'] = $page;
            $response = $this->userRepository->getAllUsers($params);
            $response['status'] = true;
        } catch (\Exception $exception) {
            Log::error(__CLASS__ . "::" . __METHOD__ . ' : ' . $exception->getMessage());
            $response = [];
            $response['status'] = false;
            $response['message'] = Lang::get('custom.something_wrong');
        }

        return Response::json($response);
    }

//    public function createUser(UserRequest $request)
    public function createUser(Request $request)
    {
        $response = [];
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|unique:users',
                'password' => 'required|confirmed',
            ], [
                'name.required' => 'Please enter name',
                'email.required' => 'Please enter email address',
                'email.unique' => 'User with email already exist',
                'password.required' => 'Please enter password',
            ]);

            if ($validator->fails()) {
                $response['status'] = false;
                $response['errors'] = $validator->errors();
                $response['message'] = Lang::get('custom.invalid_input');
                return Response::json($response);
            }

            $response = $this->userRepository->createUser($request);
        } catch (\Exception $exception) {
            Log::error(__CLASS__ . "::" . __METHOD__ . ' : ' . $exception->getMessage());
            $response = [];
            $response['status'] = false;
            $response['message'] = Lang::get('custom.something_wrong');
        }

        return Response::json($response);
    }

    public function updateUser(Request $request, $userId)
    {
        $response = [];
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|unique:users,email,' . $userId,
                'password' => 'confirmed',
            ], [
                'name.required' => 'Please enter name',
                'email.required' => 'Please enter email address',
                'email.unique' => 'User with email already exist',
                'password.required' => 'Please enter password',
            ]);

            if ($validator->fails()) {
                $response['status'] = false;
                $response['errors'] = $validator->errors();
                $response['message'] = Lang::get('custom.invalid_input');
                return Response::json($response);
            }

            $response = $this->userRepository->updateUser($userId, $request);
        } catch (\Exception $exception) {
            Log::error(__CLASS__ . "::" . __METHOD__ . ' : ' . $exception->getMessage());
            $response = [];
            $response['status'] = false;
            $response['message'] = Lang::get('custom.something_wrong');
        }

        return Response::json($response);
    }

    public function deleteUser($userId)
    {
        $response = [];
        try {
            $response = $this->userRepository->deleteUser($userId);
        } catch (\Exception $exception) {
            Log::error(__CLASS__ . "::" . __METHOD__ . ' : ' . $exception->getMessage());
            $response = [];
            $response['status'] = false;
            $response['message'] = Lang::get('custom.something_wrong');
        }

        return Response::json($response);
    }
}
