<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

use App\Models\Booking;

class StoreBookingRequest extends FormRequest
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
            'room_id' => 'required|exists:rooms,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'purpose' => 'required|string|max:1000',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $conflict = Booking::where('room_id', $this->room_id)
                ->where('date', $this->date)
                ->whereIn('status', ['pending', 'approved'])
                ->where(function ($query) {
                    $query->where('start_time', '<', $this->end_time)
                          ->where('end_time', '>', $this->start_time);
                })
                ->exists();

            if ($conflict) {
                $validator->errors()->add('start_time', 'Jadwal sudah digunakan');
            }
        });
    }
}
