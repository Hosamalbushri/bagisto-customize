<?php
namespace Webkul\DeliveryAgents\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class MassAddRequest extends FormRequest
{
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
            'indices'      => ['required', 'array'],
            'indices.*'    => ['integer'],
        ];
    }
}
