<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Все пользователи
     *
     * @return array
     */
    public function index()
    {
        $users = User::all()->toArray();
        return array_reverse($users);
    }

    /**
     * Создание пользователя
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'password' => 'required',
        ]);

        try {
            $user = new User($validated);
            $user->save();
            return response()->json(['message' => 'User created!']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'User creation error!'], 409);
        }
    }

    /**
     * Инфа о пользователе
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = User::find($id);
        return response()->json($user);
    }

    /**
     * Обновить пользователя
     *
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ]);

        try {
            $user = User::find($id);
            $user->update($validated);
            return response()->json(['message' => 'User updated!']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'User update error!'], 409);
        }
    }

    /**
     * Удалить пользователя
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return response()->json('User deleted!');
    }

    /**
     * Создание/обновление данных
     *
     * @param Request $request
     * @param null $id
     * @return bool
     */
    private function Save(Request $request, $id = null)
    {



    }
}
