<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Auth;
use App\Models\Lyric;

class UpdateLyric extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $lyric = Lyric::find($this->route('id'));

        return $lyric && $this->user()->can('update', $lyric);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'artist' => 'required',
            'yt_video_id' => [
                'bail',
                'required',
                'available',
                Rule::unique('lyrics', 'link_id')->ignore($this->route('id'))->where(function ($query) {
                    $query->where('user_id', Auth::id())->orWhere('published', 1);
                }),
            ],
            'lyric_file' => 'bail|file|mimes:txt,srt|max:500|is_srt_format',
        ];
    }

    protected function getValidatorInstance() {
        $input = $this->all();
        if ($input['yt_link']) {
            $ytVideoId = parse_yturl($input['yt_link']);
            $input['yt_video_id'] = $ytVideoId;
        }

        if ($this->hasfile('lyric_file')) {
            $input['lyric_file'] = $this->file('lyric_file');
        }

        $this->merge($input);

        return parent::getValidatorInstance();
    }
}
