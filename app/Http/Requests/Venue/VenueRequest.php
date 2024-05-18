<?php

namespace App\Http\Requests\Venue;

use Illuminate\Foundation\Http\FormRequest;

class VenueRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Static rules for other fields
        $rules = [
            'name' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'capacity' => 'required|integer|min:0',
            'governorate' => 'required|in:Aleppo,Al-Ḥasakah,Al-Qamishli,Al-Qunayṭirah,Al-Raqqah,Al-Suwayda,Damascus,Daraa,Dayr al-Zawr,Ḥamah,Homs,Idlib,Latakia,Rif Dimashq',
            'location_description' => 'required|string|max:1000',
            'location_description_ar' => 'required|string|max:1000',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'contact_number' => 'required|max:25',
            'description' => 'required|string|max:1000',
            'description_ar' => 'required|string|max:1000',
            'profile' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];

        // Add dynamic rules for albums
        $albumFields = $this->collectAlbumFields();
        foreach ($albumFields as $albumField) {
            $rules[$albumField['name']] = 'required|string|max:255';
            $rules[$albumField['images']] = 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
            $rules[$albumField['videos']] = 'mimes:mp4,mov,avi,flv|max:20000'; // Example for video validation
        }

        return $rules;
    }

    /**
     * Collect fields related to the albums from the request.
     *
     * @return array
     */
    protected function collectAlbumFields(): array
    {
        $albumFields = [];
        $input = $this->all();

        foreach ($input as $key => $value) {
            if (preg_match('/^album-\d+-name$/', $key)) {
                $index = preg_replace('/[^0-9]/', '', $key);
                $albumFields[] = [
                    'name' => "album-{$index}-name",
                    'images' => "album-{$index}-images.*",
                    'videos' => "album-{$index}-videos.*",
                ];
            }
        }

        return $albumFields;
    }
}
