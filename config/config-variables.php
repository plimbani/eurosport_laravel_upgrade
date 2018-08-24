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
	    "goal_difference" => "Goal difference",
	    "goals_for" => "Goals for",
	    "goal_ratio" => "Goal ratio",
	    "matches_won" => "Matches won"
	  ],
    "team_colors" => [
        "#FFFFFF" => "White",
        "#000000" => "Black",
        "#A7A5A6" => "Grey",
        "#034694" => "Blue",
        "#6CABDD" => "Sky blue",
        "#132257" => "Navy blue",
        "#57175E" => "Purple",
        "#FBE122" => "Yellow",
        "#FF3C00" => "Orange",
        "#D4A12A" => "Gold",
        "#D3BC8D" => "Light gold",
        "#EF0107" => "Red",
        "#6C1D45" => "Burgundy",
        "#0DB14B" => "Green",
        "#003F2D" => "Dark green",
        "#008E97" => "Teal",
    ]
];