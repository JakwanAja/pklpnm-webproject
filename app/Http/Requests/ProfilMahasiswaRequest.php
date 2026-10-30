<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfilMahasiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->peran === 'mahasiswa';
    }

    public function rules(): array
    {
        $profilId = Auth::user()->profilMahasiswa?->id;

        return [
            'nama'            => ['required', 'string', 'max:150'],
            'npm'             => ['required', 'string', 'max:30', Rule::unique('profil_mahasiswa', 'npm')->ignore($profilId)],
            'jenis_kelamin'   => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'program_studi'   => ['required', 'string', 'max:100'],
            'kelas'           => ['required', 'string', 'max:30'],
            'alamat_ktp'      => ['required', 'string'],
            'alamat_domisili' => ['required', 'string'],
            'no_hp'           => ['required', 'string', 'max:30'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required'            => 'Nama lengkap wajib diisi.',
            'nama.max'                 => 'Nama lengkap maksimal 150 karakter.',
            'npm.required'             => 'NPM wajib diisi.',
            'npm.max'                  => 'NPM maksimal 30 karakter.',
            'npm.unique'               => 'NPM sudah digunakan oleh mahasiswa lain.',
            'jenis_kelamin.required'   => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in'         => 'Jenis kelamin harus Laki-laki atau Perempuan.',
            'program_studi.required'   => 'Program studi wajib diisi.',
            'program_studi.max'        => 'Program studi maksimal 100 karakter.',
            'kelas.required'           => 'Kelas wajib diisi.',
            'kelas.max'                => 'Kelas maksimal 30 karakter.',
            'alamat_ktp.required'      => 'Alamat sesuai KTP wajib diisi.',
            'alamat_domisili.required' => 'Alamat domisili wajib diisi.',
            'no_hp.required'           => 'Nomor HP wajib diisi.',
            'no_hp.max'                => 'Nomor HP maksimal 30 karakter.',
        ];
    }
}
