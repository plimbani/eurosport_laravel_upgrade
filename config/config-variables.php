<?php


return [
    "is_score_auto_update" => env('SCORE_AUTO_UPDATE'),
    "is_automate_match_schedule_enabled" => env('AUTOMATE_MATCH_SCHEDULING_ENABLED'),
    "reset_password_interval" => env('RESET_PASSWORD_INTERVAL'),
    "signed_url_interval" => env('SIGNED_URL_INTERVAL'),
    "website_preview_url" => env('WEBSITE_PREVIEW_URL'),
    "preview_url_expire_time" => env('PREVIEW_URL_EXPIRE_TIME'),
];