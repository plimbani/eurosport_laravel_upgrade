<?php

namespace App\Contracts;

interface PresentationContract
{
    /*
     * Get matches and standings of age category
     *
     * @return response
     */
    public function getMatchesAndStandingsOfAgeCategory($ageCategoryId);
}
