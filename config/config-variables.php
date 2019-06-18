<?php


return [
    "is_score_auto_update" => env('SCORE_AUTO_UPDATE'),
    "is_automate_match_schedule_enabled" => env('AUTOMATE_MATCH_SCHEDULING_ENABLED'),
    "reset_password_interval" => env('RESET_PASSWORD_INTERVAL'),
    "signed_url_interval" => env('SIGNED_URL_INTERVAL'),
    "website_preview_url" => env('WEBSITE_PREVIEW_URL'),
    "preview_url_expire_time" => env('PREVIEW_URL_EXPIRE_TIME'),
    "app_url" => env('APP_URL'),
    
    "category_rules" => [
	    "match_points" => "Match points",
	    "goal_difference" => "Goal difference",
	    "goals_for" => "Goals for",
        "head_to_head" => "Head to head",
	    "goal_ratio" => "Goal ratio",
	    "matches_won" => "Matches won"
	  ],
      "category_rules_info" => [
        "match_points" => "The points total for games won, drawn or lost",
        "goal_difference" => "Number of goals scored minus goals conceded",
        "goals_for" => "Number of goals scored",
        "head_to_head" => "When teams are tied on the same points league table positions are determined by the outcome of games played against each other",
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
    ],

    "current_layout" => env('CURRENT_LAYOUT'),
    "google_play_store_link" => env('GOOGLE_PLAY_STORE_LINK'),
    "google_play_store_deep_link" =>env('GOOGLE_PLAY_STORE_DEEP_LINK'),
    "apple_store_link" => env('APPLE_STORE_LINK'),
    "apple_store_deep_link" =>env('APPLE_STORE_DEEP_LINK'),

    "age_category_color" => [
        '#C2B182', '#b0e0e6', '#DCB8D4', '#ffe4e1', '#0099cc', '#ffa500', '#6dc066', '#ffff00','#d3ffce',
        '#c39797', '#00A998', '#ffc3a0', '#f5f5dc', '#ffd700', '#cbbeb5', '#1874CD', '#ffc0cb', '#a0db8e',
        '#CD6600', '#008080', '#ff0000', '#00ffff', '#40e0d0', '#ff7373', '#e6e6fa', '#0000ff', '#7fffd4', 
        '#333333', '#faebd7', '#003366', '#fa8072', '#800080', '#20b2aa', '#ffb6c1', '#c6e2ff', '#00ff00',
        '#f6546a', '#f08080', '#468499', '#088da5', '#fff68f', '#ff6666', '#00ced1', '#66cdaa', '#800000',
        '#660066', '#ff00ff', '#D8BFD8', '#c0d6e4', '#0e2f44', '#ff7f50', '#ffdab9', '#990000', '#daa520',
        '#8b0000', '#b4eeb4', '#afeeee', '#ffff66', '#81d8d0', '#b6fcd5', '#66cccc', '#00ff7f', '#ccff00',
        '#cc0000', '#8a2be2', '#ff4040', '#3399ff', '#3b5998', '#794044', '#ff4444', '#000080', '#6897bb',
        '#BBF47F', '#31698a', '#191970', '#191919', '#92C685', '#4169e1', '#B0171F', '#FFBBFF', '#7D26CD',
        '#27408B', '#00C78C', '#3D9140', '#00EE00', '#EEEE00', '#FF9912', '#F4A460', '#8B4C39', '#CD0000',
        '#8E8E38', '#FFEC8B', '#EE9A49', '#CD8162', '#BBFFFF', '#008B8B', '#9F79EE', '#EE3A8C', '#47CE6E',
        '#C2A9FD', '#D5FD30', '#CACA8E', '#8D8812', '#0075EA', '#F0FF18', '#60262E', '#B2F3B7', '#532C5E'
    ],
    "age_category_font_color" => [
        "#000000", "#000000", "#000000", "#000000", "#000000", "#000000", "#000000", "#000000", "#000000",
        "#000000", "#000000", "#000000", "#000000", "#000000", "#000000", "#000000", "#000000", "#000000",
        "#000000", "#ffffff", "#000000", "#000000", "#000000", "#000000", "#000000", "#ffffff", "#000000",
        "#ffffff", "#000000", "#ffffff", "#000000", "#ffffff", "#000000", "#000000", "#000000", "#000000",
        "#000000", "#000000", "#000000", "#000000", "#000000", "#000000", "#000000", "#000000", "#ffffff",
        "#ffffff", "#000000", "#000000", "#000000", "#ffffff", "#000000", "#000000", "#ffffff", "#000000",
        "#ffffff", "#000000", "#000000", "#000000", "#000000", "#000000", "#000000", "#000000", "#000000",
        "#ffffff", "#000000", "#000000", "#000000", "#ffffff", "#ffffff", "#000000", "#ffffff", "#000000",
        "#000000", "#ffffff", "#ffffff", "#ffffff", "#000000", "#ffffff", "#ffffff", "#000000", "#ffffff",
        "#ffffff", "#000000", "#000000", "#000000", "#000000", "#000000", "#000000", "#000000", "#000000",
        "#000000", "#000000", "#000000", "#000000", "#000000", "#000000", "#000000", "#000000", "#000000",
        "#000000", "#000000", "#000000", "#000000", "#000000", "#000000", "#ffffff", "#000000", "#ffffff",
    ],
    "match_idle_time" => env('MATCH_IDLE_TIME'),
    "current_layout" => env('CURRENT_LAYOUT'),
    "enable_logs_ios" => env('ENABLE_LOGS_IOS'),
    "enable_logs_android" => env('ENABLE_LOGS_ANDROID')
];