<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = Auth::user();

        return $user instanceof User && $user->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'name'      => 'sometimes|string|max:255',
            'email'     => 'sometimes|email|unique:users,email,' . $this->route('id'),
            'password'  => 'sometimes|string|min:8',
            'is_active' => 'sometimes|boolean',
            'role'      => 'sometimes|in:admin,doctor,asistente',
        ];
    }
}