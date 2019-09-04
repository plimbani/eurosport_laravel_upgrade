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

function rounds() {
    if(isset($templateData['tournament_competation_format'])) {
      return $templateData['tournament_competation_format']['format_name'];
    }
    return [];
}

function getMainNoOfRoundCount() {
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

function getRoundRobinUniqueTeams($matches) {
    $uniqueTeamsArray = [];
    foreach($matches as $match)
        $modifiedMatchNumber = str_replace('CAT.', $groupName + '-' + $categoryAge + '-', $match['match_number']);

        if(isset($fixtures[$modifiedMatchNumber]) && $fixtures[$modifiedMatchNumber]['home_team'] !== 0) {
            $uniqueTeamsArray[] = $fixtures[$modifiedMatchNumber]['home_team_name'];
        } else {
            if(strpos($match['display_home_team_placeholder_name'], ".") !== false) {
                $matchNumber = explode(".", $match['match_number']);
                $homeAwayTeam = explode("-", $matchNumber[count($matchNumber) - 1]);
                if(strpos($homeAwayTeam[0], 'WR') !== false) {
                    $uniqueTeamsArray[] = 'Winner ' + $match['display_home_team_placeholder_name'];
                }
                if(strpos($homeAwayTeam[0], 'LR') !== false) {
                    $uniqueTeamsArray[] = 'Loser ' + $match['display_home_team_placeholder_name'];
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
                    $uniqueTeamsArray[] = 'Winner ' + $match['display_away_team_placeholder_name'];
                }
                if(strpos($homeAwayTeam[1], 'LR') !== false) {
                    $uniqueTeamsArray[] = 'Loser ' + $match['display_away_team_placeholder_name'];
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

function getPlacingTeam($match, $teamType) {
    $matchNumber = str_replace('CAT.', $groupName + '-' + $categoryAge + '-', $match['match_number']);
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

function getPlacingWinnerLoserTeam($match, $teamType) {
    $modifiedMatchNumber = str_replace('CAT.', $groupName + '-' + $categoryAge + '-', $match['match_number']);
    if($teamType === 'home' && isset($fixtures[$modifiedMatchNumber]) && $fixtures[$modifiedMatchNumber]['home_team'] !== 0) {
        return $fixtures[$modifiedMatchNumber]['home_team_name'];
    }

    if($teamType === 'away' && isset($fixtures[$modifiedMatchNumber]) && $[$modifiedMatchNumber]['away_team'] !== 0) {
        return $fixtures[$modifiedMatchNumber]['away_team_name'];
    }

    $matchNumber = explode(".", $match['match_number']);
    $homeAwayTeam = explode("-", $matchNumber[count($matchNumber) - 1]);
    if($teamType === 'home') {
        if(strpos($homeAwayTeam[0], 'WR') !== false) {
          return 'Winner ' + $match['display_home_team_placeholder_name'];
        }
        if(strpos($homeAwayTeam[0], 'LR') !== false) {
          return 'Loser ' + $match['display_home_team_placeholder_name'];
        }
        return $match['display_home_team_placeholder_name'];
    }

    if($teamType === 'away') {
        if(strpos($homeAwayTeam[1], 'WR') !== false) {
          return 'Winner ' + $match['display_away_team_placeholder_name'];
        }
        if(strpos($homeAwayTeam[1], 'LR') !== false) {
          return 'Loser ' + $match['display_away_team_placeholder_name'];
        }
        return $match['display_away_team_placeholder_name'];
    }
},

function getDivisionRounds() {
    $divisions = [];
    if(isset($templateData['tournament_competation_format']) && isset($templateData['tournament_competation_format']['divisions'])) {
        foreach($templateData['tournament_competation_format']['divisions'] as $division) {
            foreach($division['format_name'] as $roundIndex=>$round) {
                if(isset($divisions[$roundIndex])) {
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

function checkForMatchNumberOrRankingInPosition($roundType, $matchOrRanking) {
    $dependentType = $roundType === 'round_robin' ? 'ranking' : 'match';
    $filteredPositions = array_filter($templateData['tournament_positions'], function($o) {
        if($dependentType === 'ranking') {
            return $o['ranking'] === $matchOrRanking;
        }
        if($dependentType === 'match') {
            return $o['match_number'] === $matchOrRanking;
        }
    });
    if(count($filteredPositions) > 0) {
      if($roundType === 'placing_match' && count($filteredPositions) === 2) {
        $winnerPosition = reset(array_filter($filteredPositions, function($o) { return $o['result_type'] === 'winner'; }));
        $loserPosition = reset(array_filter($filteredPositions, function($o) { return $o['result_type'] === 'loser'; }));
        if($winnerPosition['position'] === 1 && $loserPosition['position'] === 2) {
          return "Final";
        }
        return "Place " + $winnerPosition['position'] + "-" + $loserPosition['position'];
      }
      return $filteredPositions[0]['position'];
    }
    return false;
}

function getMatchNumber($displayMatchNumber) {
    $matchCode = explode('.',$displayMatchNumber);
    return $matchCode['1'].'.'.$matchCode['2'];
}

function isAnyRankingInPosition($groupName, $groupCount) {
    $isAnyRankingInPosition = false;
    for($i=0; $i<$groupCount; $i++) {
        if((checkForMatchNumberOrRankingInPosition('round_robin', ($i+1) + $groupName)) !== false) {
            $isAnyRankingInPosition = true;
        }
    }
    return $isAnyRankingInPosition;
}

function getRoundRobinAssignedTeam($groupName, $teamIndex) {
    $assignedTeam = reset(array_filter($assignedTeams, function($o) { return $o['group_name'] === "Group-" . $groupName . $teamIndex; }));
    if($assignedTeam) {
      return $assignedTeam['name'];
    }
    return '#' . $teamIndex;
}