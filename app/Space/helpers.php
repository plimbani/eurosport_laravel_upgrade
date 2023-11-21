<?php

use Laraspace\Space\Settings\Setting;

/**
 * Set Active Path
 *
 * @param  string  $active
 * @return string
 */
function set_active($path, $active = 'active')
{

    return call_user_func_array('Request::is', (array) $path) ? $active : '';

}

/**
 * @return mixed
 */
function is_url($path)
{
    return call_user_func_array('Request::is', (array) $path);
}

/**
 * @return string
 */
function clean_slug($string)
{
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

    return \Illuminate\Support\Str::lower(preg_replace('/[^A-Za-z0-9\-]/', '', $string)); // Removes special chars.
}

/**
 * @return null
 */
function get_setting($set)
{

    $setting = Setting::whereOption($set)->first();

    if ($setting) {
        return $setting->value;
    } else {
        return null;
    }
}

/**
 * @return $haystack
 */
function array_key_exists_r($needle, $haystack)
{
    $result = array_key_exists($needle, $haystack);
    if ($result) {
        return $result;
    }
    foreach ($haystack as $v) {
        if (is_array($v)) {
            $result = array_key_exists_r($needle, $v);
        }
        if ($result) {
            return $result;
        }
    }

    return $result;
}

function rounds($templateData)
{
    if (isset($templateData['tournament_competation_format'])) {
        return $templateData['tournament_competation_format']['format_name'];
    }

    return [];
}

function getMainNoOfRoundCount($templateData)
{
    if (isset($templateData['tournament_competation_format'])) {
        return count($templateData['tournament_competation_format']['format_name']);
    }

    return 0;
}

function getGroupType($group)
{
    $groupName = $group['name'];
    if (isset($group['actual_name'])) {
        $groupName = $group['actual_name'];
    }
    $groupNameArray = explode('-', $groupName);

    return $groupNameArray[0];
}

function getRoundRobinUniqueTeams($fixtures, $matches, $groupName, $categoryAge)
{
    $uniqueTeamsArray = [];
    foreach ($matches as $match) {
        $modifiedMatchNumber = str_replace('CAT.', $groupName.'-'.$categoryAge.'-', $match['match_number']);
        $matchNumber = explode('.', $match['match_number']);
        $homeAwayTeam = explode('-', $matchNumber[count($matchNumber) - 1]);

        if (isset($fixtures[$modifiedMatchNumber]) && $fixtures[$modifiedMatchNumber]['home_team'] !== 0) {
            $uniqueTeamsArray[] = ['name' => $fixtures[$modifiedMatchNumber]['home_team_name'], 'code' => $homeAwayTeam[0]];
        } else {
            $teamName = '';
            if (strpos($match['display_home_team_placeholder_name'], '.') !== false) {
                if (strpos($homeAwayTeam[0], 'WR') !== false) {
                    $teamName = 'Winner '.$match['display_home_team_placeholder_name'];
                }
                if (strpos($homeAwayTeam[0], 'LR') !== false) {
                    $teamName = 'Loser '.$match['display_home_team_placeholder_name'];
                }
            } else {
                $teamName = $match['display_home_team_placeholder_name'];
            }
            $uniqueTeamsArray[] = ['name' => $teamName, 'code' => $homeAwayTeam[0]];
        }

        if (isset($fixtures[$modifiedMatchNumber]) && $fixtures[$modifiedMatchNumber]['away_team'] !== 0) {
            $uniqueTeamsArray[] = ['name' => $fixtures[$modifiedMatchNumber]['away_team_name'], 'code' => $homeAwayTeam[1]];
        } else {
            $teamName = '';
            if (strpos($match['display_away_team_placeholder_name'], '.') !== false) {
                if (strpos($homeAwayTeam[1], 'WR') !== false) {
                    $teamName = 'Winner '.$match['display_away_team_placeholder_name'];
                }
                if (strpos($homeAwayTeam[1], 'LR') !== false) {
                    $teamName = 'Loser '.$match['display_away_team_placeholder_name'];
                }
            } else {
                $teamName = $match['display_away_team_placeholder_name'];
            }
            $uniqueTeamsArray[] = ['name' => $teamName, 'code' => $homeAwayTeam[1]];
        }
    }

    $tempArr = array_unique(array_column($uniqueTeamsArray, 'name'));

    return array_intersect_key($uniqueTeamsArray, $tempArr);
}

function checkIfWinnerLoserMatch($matchNumber)
{
    return strpos($matchNumber, 'WR') !== false || strpos($matchNumber, 'LR') !== false;
}

function getPlacingTeam($fixtures, $match, $teamType, $groupName, $categoryAge)
{
    $matchNumber = str_replace('CAT.', $groupName.'-'.$categoryAge.'-', $match['match_number']);
    if ($teamType === 'home') {
        if (isset($fixtures[$matchNumber]) && $fixtures[$matchNumber]['home_team'] !== 0) {
            return $fixtures[$matchNumber]['home_team_name'];
        }

        return $match['display_home_team_placeholder_name'];
    }

    if ($teamType === 'away') {
        if (isset($fixtures[$matchNumber]) && $fixtures[$matchNumber]['away_team'] !== 0) {
            return $fixtures[$matchNumber]['away_team_name'];
        }

        return $match['display_away_team_placeholder_name'];
    }
}

function getHomeAndAwayTeamScore($fixtures, $match, $teamType, $groupName, $categoryAge)
{
    $matchNumber = str_replace('CAT.', $groupName.'-'.$categoryAge.'-', $match['match_number']);
    $matchScoreValue = '';
    if ($teamType === 'home') {
        if (isset($fixtures[$matchNumber]) && $fixtures[$matchNumber]['hometeam_score'] !== null) {
            $matchScoreValue = $fixtures[$matchNumber]['hometeam_score'];
        }

        return $matchScoreValue;
    }

    if ($teamType === 'away') {
        if (isset($fixtures[$matchNumber]) && $fixtures[$matchNumber]['awayteam_score'] !== null) {
            return $fixtures[$matchNumber]['awayteam_score'];
        }

        return $matchScoreValue;
    }
}

function getMatchDetail($fixtures, $match, $groupName, $categoryAge)
{
    $matchNumber = str_replace('CAT.', $groupName.'-'.$categoryAge.'-', $match['match_number']);
    if (isset($fixtures[$matchNumber])) {
        return [
            'is_scheduled' => $fixtures[$matchNumber]['is_scheduled'],
            'pitch_name' => $fixtures[$matchNumber]['pitch_name'],
            'venue_name' => $fixtures[$matchNumber]['venue_name'],
            'match_datetime' => $fixtures[$matchNumber]['match_datetime'],
            'is_result_override' => $fixtures[$matchNumber]['is_result_override'],
            'match_winner' => $fixtures[$matchNumber]['match_winner'],
            'home_team' => $fixtures[$matchNumber]['home_team'],
            'away_team' => $fixtures[$matchNumber]['away_team'],
        ];
    }

    return null;
}

function getPlacingWinnerLoserTeam($fixtures, $match, $teamType, $groupName, $categoryAge)
{
    $modifiedMatchNumber = str_replace('CAT.', $groupName.'-'.$categoryAge.'-', $match['match_number']);
    if ($teamType === 'home' && isset($fixtures[$modifiedMatchNumber]) && $fixtures[$modifiedMatchNumber]['home_team'] !== 0) {
        return $fixtures[$modifiedMatchNumber]['home_team_name'];
    }

    if ($teamType === 'away' && isset($fixtures[$modifiedMatchNumber]) && $fixtures[$modifiedMatchNumber]['away_team'] !== 0) {
        return $fixtures[$modifiedMatchNumber]['away_team_name'];
    }

    $matchNumber = explode('.', $match['match_number']);
    $homeAwayTeam = explode('-', $matchNumber[count($matchNumber) - 1]);
    if ($teamType === 'home') {
        if (strpos($homeAwayTeam[0], 'WR') !== false) {
            return 'Winner '.$match['display_home_team_placeholder_name'];
        }
        if (strpos($homeAwayTeam[0], 'LR') !== false) {
            return 'Loser '.$match['display_home_team_placeholder_name'];
        }

        return $match['display_home_team_placeholder_name'];
    }

    if ($teamType === 'away') {
        if (strpos($homeAwayTeam[1], 'WR') !== false) {
            return 'Winner '.$match['display_away_team_placeholder_name'];
        }
        if (strpos($homeAwayTeam[1], 'LR') !== false) {
            return 'Loser '.$match['display_away_team_placeholder_name'];
        }

        return $match['display_away_team_placeholder_name'];
    }
}

function getDivisionRounds($templateData)
{
    $divisions = [];
    if (isset($templateData['tournament_competation_format']) && isset($templateData['tournament_competation_format']['divisions'])) {
        foreach ($templateData['tournament_competation_format']['divisions'] as $division) {
            foreach ($division['format_name'] as $roundIndex => $round) {
                if (! isset($divisions[$roundIndex])) {
                    $divisions[$roundIndex] = [];
                    $divisions[$roundIndex]['match_type'] = [];
                }
                $divisions[$roundIndex]['match_type'] = array_merge($divisions[$roundIndex]['match_type'], $round['match_type']);
            }
        }

        return $divisions;
    }

    return $divisions;
}

function getGroupName($groupName)
{
    $groupName = explode('-', $groupName);

    return $groupName[1];
}

function checkForMatchNumberOrRankingInPosition($templateData, $roundType, $matchOrRanking)
{
    $dependentType = $roundType === 'round_robin' ? 'ranking' : 'match';
    $filteredPositions = array_filter($templateData['tournament_positions'], function ($o) use ($dependentType, $matchOrRanking) {
        if ($dependentType === 'ranking' && isset($o['ranking'])) {
            return $o['ranking'] === $matchOrRanking;
        }
        if ($dependentType === 'match' && isset($o['match_number'])) {
            return $o['match_number'] === $matchOrRanking;
        }
    });
    $filteredPositions = array_values($filteredPositions);
    if (count($filteredPositions) > 0) {
        if ($roundType === 'placing_match' && count($filteredPositions) === 2) {
            $winnerPosition = array_filter($filteredPositions, function ($o) {
                return $o['result_type'] === 'winner';
            });
            $winnerPosition = reset($winnerPosition);
            $loserPosition = array_filter($filteredPositions, function ($o) {
                return $o['result_type'] === 'loser';
            });
            $loserPosition = reset($loserPosition);
            if ($winnerPosition['position'] === 1 && $loserPosition['position'] === 2) {
                return 'Final';
            }

            return 'Place '.$winnerPosition['position'].'-'.$loserPosition['position'];
        }

        return $filteredPositions[0]['position'];
    }

    return false;
}

function getMatchNumber($displayMatchNumber)
{
    $matchCode = explode('.', $displayMatchNumber);

    return $matchCode['1'].'.'.$matchCode['2'];
}

function isAnyRankingInPosition($templateData, $groupName, $groupCount)
{
    $isAnyRankingInPosition = false;
    for ($i = 0; $i < $groupCount; $i++) {
        if ((checkForMatchNumberOrRankingInPosition($templateData, 'round_robin', ($i + 1).$groupName)) !== false) {
            $isAnyRankingInPosition = true;
        }
    }

    return $isAnyRankingInPosition;
}

function getRoundRobinAssignedTeam($assignedTeams, $groupName, $teamIndex, $tournamentHasStandings = false)
{
    if ($tournamentHasStandings) {
        $assignedTeams = array_values(array_filter($assignedTeams, function ($o) use ($groupName) {
            return $o['assigned_group'] === 'Group-'.$groupName;
        }));
        $assignedTeam = '';
        if (isset($assignedTeams[$teamIndex - 1])) {
            $assignedTeam = $assignedTeams[$teamIndex - 1];
        }
    } else {
        $assignedTeam = array_filter($assignedTeams, function ($o) use ($groupName, $teamIndex) {
            return $o['group_name'] === 'Group-'.$groupName.$teamIndex;
        });
        $assignedTeam = reset($assignedTeam);
    }

    if ($assignedTeam) {
        return $assignedTeam['name'];
    }

    return '#'.$teamIndex;
}

function getAllRoundGroups($roundIndex, $groups)
{
    if ($roundIndex !== 0) {
        return $groups;
    }
    $allGroups = [];
    foreach ($groups as $group) {
        $allGroups[] = $group;
        if (isset($group['dependent_groups'])) {
            foreach ($group['dependent_groups'] as $dependentGroup) {
                $allGroups[] = $dependentGroup;
            }
        }
    }

    return $allGroups;
}

function getColorCodeOfMatches($allMatches)
{
    $matchesWithColorCode = [];
    $homeAwayTeamWithColorCode = [];
    $allColors = config('config-variables.template_graphic_colors');
    $colorIndex = 0;
    foreach ($allMatches as $matchDetail) {
        $matchNumber = $matchDetail['match_number'];
        $matchNumberArray = explode('.', $matchDetail['match_number']);

        if (strpos($matchNumberArray[1], 'PM') !== false) {
            $colorCode = $allColors[$colorIndex];
            if ($colorIndex === count($allColors) - 1) {
                $colorIndex = 0;
            }

            $homeAwayTeams = $matchNumberArray[count($matchNumberArray) - 1];
            $searchResults = [];
            $searchForWinner = null;
            $searchForLoser = null;

            if (strpos($homeAwayTeams, '_WR') === false && strpos($homeAwayTeams, '_LR') === false) {
                $searchForWinner = str_replace('-', '_', $homeAwayTeams).'_WR';
                $searchForLoser = str_replace('-', '_', $homeAwayTeams).'_LR';
            } else {
                $searchForWinner = $matchNumberArray[1].'_'.$matchNumberArray[2].'_WR';
                $searchForLoser = $matchNumberArray[1].'_'.$matchNumberArray[2].'_LR';
            }

            foreach ($allMatches as $o) {
                if (strpos($o['match_number'], $searchForWinner) !== false) {
                    $searchResults[] = $o;
                    $searchForWinner = (strpos($searchForWinner, 'PM') === 0) ? '('.$searchForWinner.')' : $searchForWinner;
                    $homeAwayTeamWithColorCode[$searchForWinner] = ['background' => $colorCode, 'text' => pickTextColorBasedOnBgColorSimple($colorCode, '#FFFFFF', '#595959')];
                }
                if (strpos($o['match_number'], $searchForLoser) !== false) {
                    $searchResults[] = $o;
                    $searchForLoser = (strpos($searchForLoser, 'PM') === 0) ? '('.$searchForLoser.')' : $searchForLoser;
                    $homeAwayTeamWithColorCode[$searchForLoser] = ['background' => $colorCode, 'text' => pickTextColorBasedOnBgColorSimple($colorCode, '#FFFFFF', '#595959')];
                }
            }

            if (count($searchResults) > 0 && ! isset($matchesWithColorCode[$matchNumber])) {
                $matchesWithColorCode[$matchNumber] = ['background' => $colorCode, 'text' => pickTextColorBasedOnBgColorSimple($colorCode, '#FFFFFF', '#595959')];
                $colorIndex++;
            }
        }
    }

    return ['matchesWithColorCode' => $matchesWithColorCode, 'homeAwayTeamWithColorCode' => $homeAwayTeamWithColorCode];
}

function getRandomColorCode()
{
    return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
}

function getTeamCodeInSearch($match, $type)
{
    $matchNumber = explode('.', $match['match_number']);
    $homeAwayTeams = explode('-', $matchNumber[count($matchNumber) - 1]);
    $teamInSearch = $type === 'home' ? $homeAwayTeams[0] : $homeAwayTeams[1];

    return $teamInSearch;
}

function pickTextColorBasedOnBgColorSimple($bgColor, $lightColor, $darkColor)
{
    $color = (substr($bgColor, 0, 1) === '#') ? substr($bgColor, 1, 7) : $bgColor;
    $r = hexdec(substr($bgColor, 1, 2)); // hexToR
    $g = hexdec(substr($bgColor, 3, 2)); // hexToG
    $b = hexdec(substr($bgColor, 5, 2)); // hexToB
    $yiq = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;

    return ($yiq >= 128) ? $darkColor : $lightColor;
}
