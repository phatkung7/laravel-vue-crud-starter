<?php

namespace App\Http\Requests\Kpis;

use Illuminate\Foundation\Http\FormRequest;

class KpisRequest extends FormRequest
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
        if ($this->isMethod('post')) {
            return $this->createRules();
        } elseif ($this->isMethod('put')) {
            return $this->updateRules();
        }
    }
    /**
     * Define validation rules to store method for resource creation
     *
     * @return array
     */
    public function createRules(): array
    {
        return [
            'kpi_no' => 'required',
            'kpi_title' => 'required',
            'budget_year' => 'required|numeric|min:4',
            'approve_status_id' => 'required|numeric'
        ];
    }

    /**
     * Define validation rules to update method for resource update
     *
     * @return array
     */
    public function updateRules(): array
    {
        return [
            'kpi_no' => 'required',
            'kpi_title' => 'required',
            'budget_year' => 'required|numeric|min:4',
            'approve_status_id' => 'required|numeric',
        ];
    }
}
