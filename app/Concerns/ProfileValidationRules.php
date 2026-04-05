<?php

namespace App\Concerns;

use App\Models\User;
use Illuminate\Validation\Rule;

trait ProfileValidationRules
{
    /**
     * Get the validation rules used to validate user profiles.
     *
     * @return array<string, array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>>
     */
    protected function profileRules(?int $userId = null, string $userType = 'upnvj'): array
    {
        $isGeneralUser = $userType === 'umum';

        return [
            'name' => $this->nameRules(),
            'email' => $this->emailRules($userId),
            'nim' => ['nullable', 'string', 'max:255'],
            'no_ktp' => [$isGeneralUser ? 'required' : 'nullable', 'string', 'max:255'],
            'tempat_lahir' => [$isGeneralUser ? 'required' : 'nullable', 'string', 'max:255'],
            'tanggal_lahir' => [$isGeneralUser ? 'required' : 'nullable', 'date'],
            'jenis_kelamin' => [$isGeneralUser ? 'required' : 'nullable', 'in:L,P'],
            'alamat_rumah' => [$isGeneralUser ? 'required' : 'nullable', 'string'],
            'domisili_provinsi' => [$isGeneralUser ? 'required' : 'nullable', 'string', 'max:255'],
            'domisili_kota' => [$isGeneralUser ? 'required' : 'nullable', 'string', 'max:255'],
            'domisili_kecamatan' => [$isGeneralUser ? 'required' : 'nullable', 'string', 'max:255'],
            'no_wa' => [$isGeneralUser ? 'required' : 'nullable', 'string', 'max:20'],
            'pendidikan_terakhir' => [$isGeneralUser ? 'required' : 'nullable', 'string', 'max:255'],
            'nama_institusi' => [$isGeneralUser ? 'required' : 'nullable', 'string', 'max:255'],
            'total_sks' => ['nullable', 'integer', 'min:0'],
            'status_semester' => ['nullable', 'string', 'max:255'],
            'fakultas' => ['nullable', 'string', 'max:255'],
            'program_studi' => [$isGeneralUser ? 'required' : 'nullable', 'string', 'max:255'],
            'pekerjaan' => [$isGeneralUser ? 'required' : 'nullable', 'string', 'max:255'],
            'nama_perusahaan' => ['nullable', 'string', 'max:255'],
            'jabatan' => ['nullable', 'string', 'max:255'],
            'alamat_perusahaan' => ['nullable', 'string'],
            'kode_pos_perusahaan' => ['nullable', 'string', 'max:20'],
            'no_telp_perusahaan' => ['nullable', 'string', 'max:20'],
            'email_perusahaan' => ['nullable', 'email', 'max:255'],
        ];
    }

    /**
     * Get the validation rules used to validate user names.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function nameRules(): array
    {
        return ['required', 'string', 'max:255'];
    }

    /**
     * Get the validation rules used to validate user emails.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function emailRules(?int $userId = null): array
    {
        return [
            'required',
            'string',
            'email',
            'max:255',
            $userId === null
                ? Rule::unique(User::class, 'email')
                : Rule::unique(User::class, 'email')->ignore($userId),
        ];
    }
}
