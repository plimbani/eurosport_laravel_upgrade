<?php


return [
    "is_score_auto_update" => env('SCORE_AUTO_UPDATE'),
    "is_automate_match_schedule_enabled" => env('AUTOMATE_MATCH_SCHEDULING_ENABLED'),
    "reset_password_interval" => env('RESET_PASSWORD_INTERVAL'),
    "signed_url_interval" => env('SIGNED_URL_INTERVAL'),
    "website_preview_url" => env('WEBSITE_PREVIEW_URL'),
    "preview_url_expire_time" => env('PREVIEW_URL_EXPIRE_TIME'),
    "category_rules" => [
	    "match_points" => "Match points",
        "head_to_head" => "Head to head",
	    "goal_difference" => "Goal difference",
	    "goals_for" => "Goals for",
	    "goal_ratio" => "Goal ratio",
	    "matches_won" => "Matches won"
	  ],
      "category_rules_info" => [
        "match_points" => "The points total for games won, drawn or lost",
        "head_to_head" => "Head to head",
        "goal_difference" => "Number of goals scored minus goals conceded",
        "goals_for" => "Number of goals scored",
        "goal_ratio" => "Number of goals scored divided by the number of games played",
        "matches_won" => "Number of matches won"
      ],
    "team_colors" => [
        "#000000" => "Black",
        "#034694" => "Blue",
        "#6C1D45" => "Burgundy",
        "#003F2D" => "Dark green",
        "#D4A12A" => "Gold",
        "#0DB14B" => "Green",
        "#A7A5A6" => "Grey",
        "#D3BC8D" => "Light gold",
        "#132257" => "Navy blue",
        "#FF3C00" => "Orange",
        "#57175E" => "Purple",
        "#EF0107" => "Red",
        "#6CABDD" => "Sky blue",
        "#008E97" => "Teal",
        "#FFFFFF" => "White",
        "#FBE122" => "Yellow",
    ]
];