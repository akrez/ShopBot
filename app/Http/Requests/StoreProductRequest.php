<?php

namespace App\Http\Requests;

use App\Enums\Product\ProductStatus;
use App\Facades\ActiveBlog;
use App\Rules\CommandRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
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
    public function rules()
    {
        return static::getRules($this);
    }

    public static function getRules(FormRequest $request, bool $isStore = true)
    {
        $uniqueRule = Rule::unique('products', 'code')->where('blog_id', ActiveBlog::attr('id'));
        if (! $isStore) {
            $uniqueRule = $uniqueRule->ignore($request->id);
        }

        return [
            'name' => ['required', 'max:64'],
            'code' => ['required', 'max:32', new CommandRule, $uniqueRule],
            'product_status' => [Rule::in(ProductStatus::values())],
        ];
    }
}
