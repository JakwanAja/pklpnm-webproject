<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GantiPasswordRequest extends FormRequest
{
    /**
     * Semua pengguna yang sudah login boleh menggunakan request ini.
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
            'password_baru'              => ['required', 'string', 'min:8', 'confirmed'],
            'password_baru_confirmation' => ['required'],
        ];
    }

    /**
     * Pesan validasi dalam bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'password_baru.required'              => 'Kata sandi baru wajib diisi.',
            'password_baru.string'                => 'Kata sandi baru harus berupa teks.',
            'password_baru.min'                   => 'Kata sandi baru minimal 8 karakter.',
            'password_baru.confirmed'             => 'Konfirmasi kata sandi tidak cocok.',
            'password_baru_confirmation.required' => 'Konfirmasi kata sandi wajib diisi.',
        ];
    }
}
