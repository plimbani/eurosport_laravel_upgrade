<?php

namespace App\Http\Requests\Tournament;

use App\Models\Tournament;
use App\Traits\TournamentAccess;
use Illuminate\Foundation\Http\FormRequest;

class GetTournamentBySlugRequest extends FormRequest
{
    use TournamentAccess;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $slug = $this->route('slug');
        $tournament = Tournament::where('slug', $slug)->first();
        $isTournamentPublished = $this->isTournamentPublished($tournament);
        if (! $isTournamentPublished) {
            return false;
        }

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
            //
        ];
    }
}
