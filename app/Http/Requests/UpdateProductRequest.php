<?php

namespace App\Http\Requests;

use App\Enums\Product\ProductStatus;
use App\Facades\ActiveBlog;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'max:64'],
            'code' => ['required', 'max:32', Rule::unique('products', 'code')->where('blog_id', ActiveBlog::attr('id'))->ignore($this->id)],
            'product_status' => [Rule::in(ProductStatus::values())],
        ];
    }
}
