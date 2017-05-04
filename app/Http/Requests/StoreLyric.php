<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Auth;

class StoreLyric extends FormRequest
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
            //'title' => 'required',
            //'artist' => 'required',
            'yt_video_id' => [
                'required',
                Rule::unique('lyrics', 'link_id')->where(function ($query) {
                    $query->where('user_id', Auth::id())->orWhere('published', 1);
                }),
            ],
            //'lyric_file' => 'required|file|mimes:txt,srt',
        ];
    }

    protected function getValidatorInstance() {
        $input = $this->all();
        $ytVideoId = parse_yturl($input['yt_link']);
        if ($ytVideoId) {
            $input['yt_video_id'] = $ytVideoId;
            $this->merge($input);
        }

        return parent::getValidatorInstance();
    }
}
