<?php

declare(strict_types=1);

namespace App\Http\Requests;


use App\Models\File\Enums\filePaths;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;



class FileUploadRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'template' => ['required', new Enum(filePaths::class)],
            'file' => ['required', 'file'],
            'file1' => ['file'],
            'file2' => ['file'],
            'file3' => ['file'],
            'headline' => [ 'string'],
            'paragraph' => [ 'string'],
        ];
    }
}
