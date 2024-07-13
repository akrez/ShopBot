<?php

namespace App\Http\Requests;

use App\Enums\Blog\BlogStatus as BlogBlogStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBlogRequest extends FormRequest
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
            'name' => 'required|max:64',
            'short_description' => 'required|max:120',
            'description' => 'required|max:512',
            'blog_status' => [Rule::in(BlogBlogStatus::values())],
        ];
    }
}
