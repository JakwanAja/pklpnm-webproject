<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Siapa saja yang boleh membuat request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi.
     */
    public function rules(): array
    {
        return [
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Pesan error dalam bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'email.required'    => 'Alamat email wajib diisi.',
            'email.email'       => 'Format alamat email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.string'   => 'Kata sandi harus berupa teks.',
        ];
    }

    /**
     * Label atribut dalam bahasa Indonesia.
     */
    public function attributes(): array
    {
        return [
            'email'    => 'alamat email',
            'password' => 'kata sandi',
        ];
    }
}
