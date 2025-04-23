<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;



class CreateProductsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Cho phép tất cả users gửi request
    }

    /**
     * Prepare the data for validation.
     */
    public function prepareForValidation(): void
    {
        $this->merge([
            'status' => $this->input('status', 'inactive'),
            'specifications' => $this->parseJsonOrArray($this->input('specifications')),
            'features' => $this->parseJsonOrArray($this->input('features')),
            'meta_data' => $this->parseJsonOrArray($this->input('meta_data')),
            'product_images' => $this->parseJsonOrArray($this->input('product_images')),
            'base_original_price' => (float) $this->input('base_original_price', 0),
            'sku' => $this->input('sku', $this->generateSku()),
        ]);
    }

    /**
     * Parse JSON string or return array
     */
    protected function parseJsonOrArray($value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if (is_string($value) && !empty($value)) {
            $decoded = json_decode($value, true);
            return json_last_error() === JSON_ERROR_NONE ? $decoded : [];
        }

        return [];
    }

    /**
     * Generate default SKU
     */
    protected function generateSku(): string
    {
        return 'SKU-' . uniqid() . '-' . strtoupper(substr(md5(microtime()), 0, 4));
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Product Info
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'category_id' => 'nullable|integer|exists:categories,id',

            // Pricing
            'base_price' => 'required|numeric|min:0',
            'base_original_price' => 'nullable|numeric|min:0',

            // Inventory
            'sku' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('product', 'sku')->ignore($this->route('product'))
            ],
            'status' => 'nullable|string|in:active,inactive,draft',

            // Arrays/JSON Data
            'specifications' => 'nullable|array',
            'specifications.*.key' => 'required|string|max:100',
            'specifications.*.value' => 'required|string|max:255',

            'features' => 'nullable|array',
            'features.*' => 'string|max:255',

            'meta_data' => 'nullable|array',
            'meta_data.*.key' => 'required|string|max:100',
            'meta_data.*.value' => 'required|string',

            'product_images' => 'nullable|array',
            'product_images.*' => 'required|url|max:500',
        ];
    }

    /**
     * Custom validation messages
     */
    public function messages(): array
    {
        return [
            // Product Info
            'name.required' => 'Tên sản phẩm là bắt buộc.',
            'name.max' => 'Tên sản phẩm không vượt quá 255 ký tự.',
            'description.required' => 'Mô tả sản phẩm là bắt buộc.',

            // Pricing
            'base_price.required' => 'Giá bán là bắt buộc.',
            'base_price.numeric' => 'Giá bán phải là số.',
            'base_price.min' => 'Giá bán không được âm.',

            // SKU
            'sku.unique' => 'SKU đã tồn tại trong hệ thống.',
            'sku.max' => 'SKU không vượt quá 100 ký tự.',

            // Arrays
            'specifications.*.key.required' => 'Mỗi specification cần có key.',
            'specifications.*.value.required' => 'Mỗi specification cần có value.',
            'product_images.*.url' => 'Đường dẫn ảnh không hợp lệ.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'base_price' => 'giá bán',
            'base_original_price' => 'giá gốc',
            'specifications' => 'thông số kỹ thuật',
        ];
    }

    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422)
        );
    }
}
