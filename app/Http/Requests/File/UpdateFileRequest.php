<?php
namespace TorNas\Http\Requests\File;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'                  => 'required',
            'year'                  => 'required',
            'rating'                => 'required',
            'runtime'               => 'required',
            'cover'                 => 'sometimes',
            'category'              => 'required|exists:categories,id',
            'genres'                => 'required|array',
            'genres.*'              => 'required|exists:genres,id',
            'subtitles'             => 'sometimes|array',
            'subtitles.lang'        => '',
            'subtitles.subtitles'   => 'file',
        ];
    }
}
