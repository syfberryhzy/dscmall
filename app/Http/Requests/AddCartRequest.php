<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\productSku;

class AddCartRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sku_id' => [
              'required',
              function ($attribute, $value, $fail) {
                if (!$sku = productSku::find($value)) {
                  return $fail('该商品不存在');
                }
                if (!$sku->product->on_sale) {
                  return $fail('该商品未上架');
                }
                if ($this->input('amount') > 0 && $sku->stock < $this->input('amount')) {
                  return $fail('该商品库存不足');
                }
              },
            ],
            'amount' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages()
    {
      return [
        'sku_id.required' => '请选择商品'
      ];
    }
}
