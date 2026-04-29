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
        $usesGeneralBiodata = in_array($userType, ['umum', 'mahasiswa'], true);

        return [
            'nama' => $this->nameRules(),
            'email' => $this->emailRules($userId),
            'nim' => ['nullable', 'string', 'max:255'],
            'no_ktp' => [$usesGeneralBiodata ? 'required' : 'nullable', 'string', 'max:255'],
            'tempat_lahir' => [$usesGeneralBiodata ? 'required' : 'nullable', 'string', 'max:255'],
            'tanggal_lahir' => [$usesGeneralBiodata ? 'required' : 'nullable', 'date'],
            'jenis_kelamin' => [$usesGeneralBiodata ? 'required' : 'nullable', 'in:L,P'],
            'alamat_rumah' => [$usesGeneralBiodata ? 'required' : 'nullable', 'string'],
            'no_wa' => [$usesGeneralBiodata ? 'required' : 'nullable', 'string', 'max:20'],
            'kebangsaan' => ['nullable', 'string', 'max:255'],
            'kode_pos_rumah' => [$usesGeneralBiodata ? 'required' : 'nullable', 'string', 'max:20'],
            'telp_rumah' => ['nullable', 'string', 'max:20'],
            'telp_kantor' => ['nullable', 'string', 'max:20'],
            'kualifikasi_pendidikan' => [$usesGeneralBiodata ? 'required' : 'nullable', 'string', 'max:255'],
            'total_sks' => ['nullable', 'integer', 'min:0'],
            'status_semester' => ['nullable', 'string', 'max:255'],
            'fakultas' => ['nullable', 'string', 'max:255'],
            'program_studi' => ['nullable', 'string', 'max:255'],
            'nama_perusahaan' => [$usesGeneralBiodata ? 'required' : 'nullable', 'string', 'max:255'],
            'jabatan' => ['nullable', 'string', 'max:255'],
            'alamat_perusahaan' => ['nullable', 'string'],
            'kode_pos_perusahaan' => [$usesGeneralBiodata ? 'required' : 'nullable', 'string', 'max:20'],
            'no_telp_perusahaan' => ['nullable', 'string', 'max:20'],
            'email_perusahaan' => ['nullable', 'email', 'max:255'],
            'fax_perusahaan' => ['nullable', 'string', 'max:20'],
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
