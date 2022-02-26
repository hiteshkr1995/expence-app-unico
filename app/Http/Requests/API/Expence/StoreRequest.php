<?php

namespace App\Http\Requests\API\Expence;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StoreRequest extends FormRequest
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
        $regex2Dec = "regex:/^\d+(\.\d{1,2})?$/";

        $amount = ["required", "numeric", "digits_between:1,100000", $regex2Dec];

        $validate = [];

        if ($this->type == 1) {

            $validate = [
                "amount" => $amount,
                "user_ids" => ["array"],
                "user_ids.*" => ["numeric", 'distinct', "exists:users,id"],
            ];

        }

        if ($this->type == 2) {

            $validate = [
                "users" => ["required", "array", "min:1"],
                "users.*.id" => ["numeric", "distinct:strict", "exists:users,id"],
                "users.*.amount" => $amount,
            ];

        }

        if ($this->type == 3) {

            $validate = [
                "amount" => $amount,
                "users" => ["required", "array", "min:1"],
                "users.*.id" => ["numeric", "distinct:strict", "exists:users,id"],
                "users.*.percent" => ["numeric", "digits_between:1,100", $regex2Dec],
            ];

            $percent = 0;

            foreach ($this->users as $key => $user) {
                $percent += $user['percent'];
            }

            if ($percent != 100) {

                throw ValidationException::withMessages([
                    'percent' => "Invalid Percent",
                ]);

            }

        }

        $validate["type"] = ["required", Rule::in([1, 2, 3])];

        return $validate;
    }
}
