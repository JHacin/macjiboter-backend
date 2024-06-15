<?php

namespace App\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SponsorshipWallpaperRequest extends FormRequest
{
    public function authorize(): bool
    {
        return backpack_auth()->check();
    }

    public function rules(): array
    {
        $currentId = $this->get('id');

        return [
            'month_and_year' => [
                'required',
                'date',
                Rule::unique('sponsorship_wallpapers', 'month_and_year')->ignore($currentId),
            ],
            'file_path' => [
                'required',
                'file',
                'mimes:zip',
                'max:25600' // 25600 KB = 25 MB
            ],
        ];
    }

    public function attributes(): array
    {
        return [];
    }

    public function messages(): array
    {
        return [
            'month_and_year.unique' => 'Ozadja za ta mesec in leto že obstajajo.',
            'file_path.max' => 'Datoteka ne sme biti večja od 25 MB.'
        ];
    }
}
