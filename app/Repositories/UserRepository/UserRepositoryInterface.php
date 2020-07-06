<?php


namespace App\Repositories\UserRepository;


use Illuminate\Http\Request;

interface UserRepositoryInterface
{
    public function authenticate($email, $password, $rememberMe = true);
    public function checkUserExist($email = '', $userId = 0);
    public function getAllUsers($params = array());
    public function createUser(Request$request);
    public function deleteUser($userId);
    public function updateUser($userId, Request $request);
}
