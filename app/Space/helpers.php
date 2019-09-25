<?php

use Laraspace\Space\Settings\Setting;

/**
 * Set Active Path
 *
 * @param $path
 * @param string $active
 * @return string
 */
function set_active($path, $active = 'active') {

    return call_user_func_array('Request::is', (array)$path) ? $active : '';

}

/**
 * @param $path
 * @return mixed
 */
function is_url($path){
    return call_user_func_array('Request::is', (array)$path);
}

/**
 * @param $string
 * @return string
 */
function clean_slug($string) {
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

    return \Illuminate\Support\Str::lower(preg_replace('/[^A-Za-z0-9\-]/', '', $string)); // Removes special chars.
}

/**
 * @param $set
 * @return null
 */
function get_setting($set){

    $setting = Setting::whereOption($set)->first();

    if($setting){
        return $setting->value;
    }else{
        return null;
    }
}

/**
 * @param $needle
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

function rounds($templateData) {
    if(isset($templateData['tournament_competation_format'])) {
      return $templateData['tournament_competation_format']['format_name'];
    }
    return [];
}

function getMainNoOfRoundCount($templateData) {
    if(isset($templateData['tournament_competation_format'])) {
      return count($templateData['tournament_competation_format']['format_name']);
    }
    return 0;
}

function getGroupType($group) {
    $groupName = $group['name'];
    if(isset($group['actual_name'])) {
      $groupName = $group['actual_name'];
    }
    $groupNameArray = explode('-', $groupName);
    return $groupNameArray[0];
}

function getRoundRobinUniqueTeams($fixtures, $matches, $groupName, $categoryAge) {
    $uniqueTeamsArray = [];
    foreach($matches as $match) {
        $modifiedMatchNumber = str_replace('CAT.', $groupName . '-' . $categoryAge . '-', $match['match_number']);

        if(isset($fixtures[$modifiedMatchNumber]) && $fixtures[$modifiedMatchNumber]['home_team'] !== 0) {
            $uniqueTeamsArray[] = $fixtures[$modifiedMatchNumber]['home_team_name'];
        } else {
            if(strpos($match['display_home_team_placeholder_name'], ".") !== false) {
                $matchNumber = explode(".", $match['match_number']);
                $homeAwayTeam = explode("-", $matchNumber[count($matchNumber) - 1]);
                if(strpos($homeAwayTeam[0], 'WR') !== false) {
                    $uniqueTeamsArray[] = 'Winner ' . $match['display_home_team_placeholder_name'];
                }
                if(strpos($homeAwayTeam[0], 'LR') !== false) {
                    $uniqueTeamsArray[] = 'Loser ' . $match['display_home_team_placeholder_name'];
                }
            } else {
                $uniqueTeamsArray[] = $match['display_home_team_placeholder_name'];
            }
        }

        if(isset($fixtures[$modifiedMatchNumber]) && $fixtures[$modifiedMatchNumber]['away_team'] !== 0) {
            $uniqueTeamsArray[] = $fixtures[$modifiedMatchNumber]['away_team_name'];
        } else {
            if(strpos($match['display_away_team_placeholder_name'], ".") !== false) {
                $matchNumber = explode(".", $match['match_number']);
                $homeAwayTeam = explode("-", $matchNumber[count($matchNumber) - 1]);
                if(strpos($homeAwayTeam[1], 'WR') !== false) {
                    $uniqueTeamsArray[] = 'Winner ' . $match['display_away_team_placeholder_name'];
                }
                if(strpos($homeAwayTeam[1], 'LR') !== false) {
                    $uniqueTeamsArray[] = 'Loser ' . $match['display_away_team_placeholder_name'];
                }
            } else {
                $uniqueTeamsArray[] = $match['display_away_team_placeholder_name'];
            }
        }
    }

    return array_unique($uniqueTeamsArray);
}

function checkIfWinnerLoserMatch($matchNumber) {
    return (strpos($matchNumber, "WR") !== false || strpos($matchNumber, "LR") !== false);
}

function getPlacingTeam($fixtures, $match, $teamType, $groupName, $categoryAge) {
    $matchNumber = str_replace('CAT.', $groupName . '-' . $categoryAge . '-', $match['match_number']);
    if($teamType === 'home') {
        if(isset($fixtures[$matchNumber]) && $fixtures[$matchNumber]['home_team'] !== 0) {
            return $fixtures[$matchNumber]['home_team_name'];
        }
        return $match['display_home_team_placeholder_name'];
    }

    if($teamType === 'away') {
        if(isset($fixtures[$matchNumber]) && $fixtures[$matchNumber]['away_team'] !== 0) {
            return $fixtures[$matchNumber]['away_team_name'];
        }
        return $match['display_away_team_placeholder_name'];
    }
}

function getMatchDetail($fixtures, $match, $groupName, $categoryAge) {
    $matchNumber = str_replace('CAT.', $groupName . '-' . $categoryAge . '-', $match['match_number']);
    if(isset($fixtures[$matchNumber])) {
        return [
            'is_scheduled' => $fixtures[$matchNumber]['is_scheduled'],
            'pitch_name' => $fixtures[$matchNumber]['pitch_name'],
            'venue_name' => $fixtures[$matchNumber]['venue_name'],
            'match_datetime' => $fixtures[$matchNumber]['match_datetime'],
        ];
    }
    return null;
}

function getPlacingWinnerLoserTeam($fixtures, $match, $teamType, $groupName, $categoryAge) {
    $modifiedMatchNumber = str_replace('CAT.', $groupName . '-' . $categoryAge . '-', $match['match_number']);
    if($teamType === 'home' && isset($fixtures[$modifiedMatchNumber]) && $fixtures[$modifiedMatchNumber]['home_team'] !== 0) {
        return $fixtures[$modifiedMatchNumber]['home_team_name'];
    }

    if($teamType === 'away' && isset($fixtures[$modifiedMatchNumber]) && $fixtures[$modifiedMatchNumber]['away_team'] !== 0) {
        return $fixtures[$modifiedMatchNumber]['away_team_name'];
    }

    $matchNumber = explode(".", $match['match_number']);
    $homeAwayTeam = explode("-", $matchNumber[count($matchNumber) - 1]);
    if($teamType === 'home') {
        if(strpos($homeAwayTeam[0], 'WR') !== false) {
          return 'Winner ' . $match['display_home_team_placeholder_name'];
        }
        if(strpos($homeAwayTeam[0], 'LR') !== false) {
          return 'Loser ' . $match['display_home_team_placeholder_name'];
        }
        return $match['display_home_team_placeholder_name'];
    }

    if($teamType === 'away') {
        if(strpos($homeAwayTeam[1], 'WR') !== false) {
          return 'Winner ' . $match['display_away_team_placeholder_name'];
        }
        if(strpos($homeAwayTeam[1], 'LR') !== false) {
          return 'Loser ' . $match['display_away_team_placeholder_name'];
        }
        return $match['display_away_team_placeholder_name'];
    }
}

function getDivisionRounds($templateData) {
    $divisions = [];
    if(isset($templateData['tournament_competation_format']) && isset($templateData['tournament_competation_format']['divisions'])) {
        foreach($templateData['tournament_competation_format']['divisions'] as $division) {
            foreach($division['format_name'] as $roundIndex=>$round) {
                if(!isset($divisions[$roundIndex])) {
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

function getGroupName($groupName) {
    $groupName = explode("-", $groupName);
    return $groupName[1];
}

function checkForMatchNumberOrRankingInPosition($templateData, $roundType, $matchOrRanking) {
    $dependentType = $roundType === 'round_robin' ? 'ranking' : 'match';
    $filteredPositions = array_filter($templateData['tournament_positions'], function($o) use($dependentType, $matchOrRanking) {
        if($dependentType === 'ranking' && isset($o['ranking'])) {
            return $o['ranking'] === $matchOrRanking;
        }
        if($dependentType === 'match' && isset($o['match_number'])) {
            return $o['match_number'] === $matchOrRanking;
        }
    });
    $filteredPositions = array_values($filteredPositions);
    if(count($filteredPositions) > 0) {
      if($roundType === 'placing_match' && count($filteredPositions) === 2) {
        $winnerPosition = array_filter($filteredPositions, function($o) { return $o['result_type'] === 'winner'; });
        $winnerPosition = reset($winnerPosition);
        $loserPosition = array_filter($filteredPositions, function($o) { return $o['result_type'] === 'loser'; });
        $loserPosition = reset($loserPosition);
        if($winnerPosition['position'] === 1 && $loserPosition['position'] === 2) {
          return "Final";
        }
        return "Place " . $winnerPosition['position'] . "-" . $loserPosition['position'];
      }
      return $filteredPositions[0]['position'];
    }
    return false;
}

function getMatchNumber($displayMatchNumber) {
    $matchCode = explode('.',$displayMatchNumber);
    return $matchCode['1'].'.'.$matchCode['2'];
}

function isAnyRankingInPosition($templateData, $groupName, $groupCount) {
    $isAnyRankingInPosition = false;
    for($i=0; $i<$groupCount; $i++) {
        if((checkForMatchNumberOrRankingInPosition($templateData, 'round_robin', ($i+1) . $groupName)) !== false) {
            $isAnyRankingInPosition = true;
        }
    }
    return $isAnyRankingInPosition;
}

function getRoundRobinAssignedTeam($assignedTeams, $groupName, $teamIndex) {
    $assignedTeam = array_filter($assignedTeams, function($o) use($groupName, $teamIndex) { return $o['group_name'] === "Group-" . $groupName . $teamIndex; });
    $assignedTeam = reset($assignedTeam);
    if($assignedTeam) {
      return $assignedTeam['name'];
    }
    return '#' . $teamIndex;
}

function getAllRoundGroups($roundIndex, $groups) {
    if($roundIndex !== 0) {
        return $groups;
    }
    $allGroups = [];
    foreach($groups as $group) {
        $allGroups[] = $group;
        if(isset($group['dependent_groups'])) {
            foreach($group['dependent_groups'] as $dependentGroup) {
                $allGroups[] = $dependentGroup;
            }
        }
    }
    return $allGroups;
}

function getColorCodeOfMatches($fixtures, $groupName, $categoryAge) {
    $matchesWithColorCode = [];
    $homeAwayTeamWithColorCode = [];
    foreach($fixtures as $matchNumber=>$matchDetail) {
        $matchNumberArray = explode(".", $matchDetail['match_number']);
        $modifiedMatchNumber = str_replace($groupName . '-' . $categoryAge . '-', 'CAT.', $matchNumber);
        $modifiedMatchNumberArray = explode(".", $modifiedMatchNumber);

        if(strpos($modifiedMatchNumberArray[1], "PM") !== false) {
            $colorCode = getRandomColorCode();
            while(array_key_exists($colorCode, $matchNumberArray)) {
                $colorCode = getRandomColorCode();
            }

            $homeAwayTeams = $matchNumberArray[count($matchNumberArray) - 1];
            $searchResults = [];
            $searchForWinner = null;
            $searchForLoser = null;

            if(strpos($homeAwayTeams, "_WR") === false && strpos($homeAwayTeams, "_LR") === false) {
                $searchForWinner = str_replace('-', '_', $homeAwayTeams) . '_WR';
                $searchForLoser = str_replace('-', '_', $homeAwayTeams) . '_LR';
            } else {
                $searchForWinner = $matchNumberArray[1] . '_' . $matchNumberArray[2] . '_WR';
                $searchForLoser = $matchNumberArray[1] . '_' . $matchNumberArray[2] . '_LR';
            }

            foreach($fixtures as $o) {
                if(strpos($o['match_number'], $searchForWinner) !== false) {
                    $searchResults[] = $o;
                    $homeAwayTeamWithColorCode[$searchForWinner] = ['background' => $colorCode, 'text' => pickTextColorBasedOnBgColorSimple($colorCode, '#FFFFFF', '#000000')];
                }
                if(strpos($o['match_number'], $searchForLoser) !== false) {
                    $searchResults[] = $o;
                    $homeAwayTeamWithColorCode[$searchForLoser] = ['background' => $colorCode, 'text' => pickTextColorBasedOnBgColorSimple($colorCode, '#FFFFFF', '#000000')];
                }
            }

            if(count($searchResults) > 0 && !isset($matchesWithColorCode[$modifiedMatchNumber])) {
                $matchesWithColorCode[$modifiedMatchNumber] = ['background' => $colorCode, 'text' => pickTextColorBasedOnBgColorSimple($colorCode, '#FFFFFF', '#000000')];
            }
        }
    }
    return ['matchesWithColorCode' => $matchesWithColorCode, 'homeAwayTeamWithColorCode' => $homeAwayTeamWithColorCode];
}

function getRandomColorCode() {
    return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
}

function getTeamCodeInSearch($match, $type) {
    $matchNumber = explode(".", $match['match_number']);
    $homeAwayTeams = explode("-", $matchNumber[count($matchNumber) - 1]);
    $teamInSearch = $type === 'home' ? $homeAwayTeams[0] : $homeAwayTeams[1];
    return $teamInSearch;
}

function pickTextColorBasedOnBgColorSimple($bgColor, $lightColor, $darkColor) {
    $color = (substr($bgColor, 0, 1) === '#') ? substr($bgColor, 1, 7) : $bgColor;
    $r = intval(substr($bgColor, 0, 2), 16); // hexToR
    $g = intval(substr($bgColor, 2, 4), 16); // hexToG
    $b = intval(substr($bgColor, 4, 6), 16); // hexToB
    return ((($r * 0.299) + ($g * 0.587) + ($b * 0.114)) > 186) ? $darkColor : $lightColor;
}