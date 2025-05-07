<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductFormRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'price' => 'required|numeric|min:0',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter the product name.',
            'name.string' => 'The product name must be a string.',
            'name.max' => 'The product name may not be greater than 255 characters.',

            'description.required' => 'Please enter the product description.',
            'description.string' => 'The product description must be a string.',
            'description.max' => 'The product description may not be greater than 1000 characters.',

            'price.required' => 'Please enter the price.',
            'price.numeric' => 'The price must be a number.',
            'price.min' => 'The price must be at least 0.',

            // 'featured_image.required' => 'Please upload a file.',
            'featured_image.image' => 'The uploaded file must be an image.',
            'featured_image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
            'featured_image.max' => 'The image may not be greater than 2MB.',
        ];
    }
}
