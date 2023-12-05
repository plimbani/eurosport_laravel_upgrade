<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    protected $table = 'competitions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $primaryKey = 'id';

    protected $fillable = ['id', 'tournament_id', 'tournament_competation_template_id', 'name', 'display_name', 'actual_name', 'team_size',
        'competation_type', 'actual_competition_type', 'competation_round_no', 'color_code', 'created_at', 'updated_at', 'deleted_at', 'age_category_division_id'];

    public function TournamentCompetationTemplates()
    {
        return $this->belongsTo(\App\Models\TournamentCompetationTemplates::class, 'tournament_competation_template_id');
    }

    public function scheduledFixtures()
    {
        return $this->hasMany(\App\Models\TempFixture::class, 'competition_id')->where('is_scheduled', '=', 1);
    }

    public function AgeCategoryDivision()
    {
        return $this->belongsTo(\App\Models\AgeCategoryDivision::class, 'age_category_division_id');
    }
}
