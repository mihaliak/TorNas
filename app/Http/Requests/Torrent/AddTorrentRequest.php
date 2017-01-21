<?php
namespace TorNas\Http\Requests\Torrent;

use Illuminate\Foundation\Http\FormRequest;

class AddTorrentRequest extends FormRequest
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
            'type'     => 'required|in:file,magnet',
            'torrent'  => 'required_if:type,file|file',
            'magnet'   => 'required_if:type,magnet',
            'name'     => 'required',
            'year'     => 'required',
            'rating'   => 'required',
            'runtime'  => 'required',
            'cover'    => 'required',
            'category' => 'required|exists:categories,id',
            'genres'   => 'required|array',
            'genres.*' => 'required|exists:genres,id',
        ];
    }
}
