<?php

return [
	'website_customisation_options' => [
		'colors' => [
			'#D0021B', '#FF9F00', '#417505', '#50E3C2', '#1E469D', '#9013FE'
		],
		'fonts' => [
			'Open Sans', 'Montserrat', 'Roboto', 'Lato', 'PT Sans', 'Helvetica'
		],
	],
  'website_default_pages' => [
    [
      'url' => '/',
      'name' => 'home',
      'page_name' => 'home',
      'title' => 'Home',
      'content' => null,
      'is_enabled' => 1,
      'is_published' => 1,
      'is_permission_changeable' => 0,
      'accessible_routes' => ['home.page.details']
    ],
  	[
  		'url' => '/matches',
  		'name' => 'matches',
      'page_name' => 'matches',
  		'title' => 'Matches',
      'content' => null,
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
      'accessible_routes' => ['match.page.details']
  	],
    [
      'url' => null,
      'name' => 'tournament',
      'page_name' => 'tournament',
      'title' => 'Tournament',
      'content' => null,
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
      'accessible_routes' => null,
      'children' => [
        [
          'url' => '/tournament',
          'name' => 'age_categories',
          'page_name' => 'tournament',
          'title' => 'Age categories',
          'content' => null,
          'is_enabled' => 0,
          'is_published' => 0,
          'is_permission_changeable' => 1,
          'accessible_routes' => ['tournament.page.details']
        ],
        [
          'url' => '/teams',
          'name' => 'teams',
          'page_name' => 'teams',
          'title' => 'Teams',
          'content' => null,
          'is_enabled' => 0,
          'is_published' => 0,
          'is_permission_changeable' => 1,
          'accessible_routes' => ['team.page.details']
        ],
        [
          'url' => '/rules',
          'name' => 'rules',
          'page_name' => 'rules',
          'title' => 'Rules',
          'content' => null,
          'is_enabled' => 0,
          'is_published' => 0,
          'is_permission_changeable' => 1,
          'accessible_routes' => ['rules.page.details']
        ],
        [
          'url' => '/history',
          'name' => 'history',
          'page_name' => 'history',
          'title' => 'History',
          'content' => null,
          'is_enabled' => 0,
          'is_published' => 0,
          'is_permission_changeable' => 1,
          'accessible_routes' => ['history.page.details']
        ],
      ]
    ], 
    [
      'url' => null,
      'name' => 'program',
      'page_name' => 'program',
      'title' => 'Program',
      'content' => null,
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
      'accessible_routes' => null,
      'children' => [
        [
          'url' => '/program',
          'name' => 'program_overview',
          'page_name' => 'program',
          'title' => 'Overview',
          'content' => null,
          'is_enabled' => 0,
          'is_published' => 0,
          'is_permission_changeable' => 1,
          'accessible_routes' => ['program.page.details']
        ]
      ]
    ],
    [
      'url' => null,
      'name' => 'stay',
      'page_name' => 'stay',
      'title' => 'Hospitality',
      'content' => null,
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
      'accessible_routes' => null,
      'children' => [
        [
          'url' => '/visitors',
          'name' => 'visitors',
          'page_name' => 'visitors',
          'title' => 'Check-in',
          'content' => null,
          'is_enabled' => 0,
          'is_published' => 0,
          'is_permission_changeable' => 1,
          'accessible_routes' => ['visitor.page.details'],
        ],
        [
          'url' => '/visitors/#public-transport',
          'name' => 'public_transport',
          'page_name' => 'public-transport',
          'title' => 'Public Transport',
          'content' => null,
          'is_enabled' => 0,
          'is_published' => 0,
          'is_permission_changeable' => 1,
          'accessible_routes' => ['visitor.page.details']
        ],
        [
          'url' => '/tourist-information',
          'name' => 'tourist_information',
          'page_name' => 'tourist-information',
          'title' => 'Tourist information',
          'content' => null,
          'is_enabled' => 0,
          'is_published' => 0,
          'is_permission_changeable' => 1,
          'accessible_routes' => ['tourist.page.details']
        ],
        [
          'url' => '/visitors/#tips',
          'name' => 'tips',
          'page_name' => 'tips',
          'title' => 'Tips',
          'content' => null,
          'is_enabled' => 0,
          'is_published' => 0,
          'is_permission_changeable' => 1,
          'accessible_routes' => ['visitor.page.details']
        ],
        [
          'url' => '/accommodation',
          'name' => 'accommodation',
          'page_name' => 'accommodation',
          'title' => 'Accommodation',
          'content' => null,
          'is_enabled' => 0,
          'is_published' => 0,
          'is_permission_changeable' => 1,
          'accessible_routes' => ['accommodation.page.details']
        ],
        [
          'url' => '/meals',
          'name' => 'meals',
          'page_name' => 'meals',
          'title' => 'Meals',
          'content' => null,
          'is_enabled' => 0,
          'is_published' => 0,
          'is_permission_changeable' => 1,
          'accessible_routes' => ['meals.page.details']
        ]
      ]
    ],    
  	[
  		'url' => '/venue',
  		'name' => 'venue',
      'page_name' => 'venue',
  		'title' => 'Location',
      'content' => null,
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
      'accessible_routes' => ['venue.page.details']
  	],
    [
      'url' => '/media',
      'name' => 'media',
      'page_name' => 'media',
      'title' => 'Media',
      'content' => null,
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
      'accessible_routes' => ['media.page.details']
    ],
    [
      'url' => '/contact',
      'name' => 'contact',
      'page_name' => 'contact',
      'title' => 'Contact',
      'content' => null,
      'is_enabled' => 1,
      'is_published' => 1,
      'is_permission_changeable' => 0,
      'accessible_routes' => ['contact.page.details', 'submit.enquiry']
    ]
  ],
  'permissions_not_changeable_pages' => ['home', 'contact'],
  'hide_header_menus' => ['home'],
  'current_domain' => null,
  'default_accessible_routes' => [],
  'google_api_key' => env('GOOGLE_API_KEY', ''),
  'imagePath' => [
    'website_tournament_logo' => '/assets/img/website_tournament_logo/',
    'social_sharing_graphic' => '/assets/img/social_sharing_graphic/',
    'hero_image' => '/assets/img/hero_image/',
    'welcome_image' => '/assets/img/welcome_image/',
    'organiser_logo' => '/assets/img/organiser/',
    'sponsor_logo' => '/assets/img/sponsor/',
    'photo' => '/assets/img/photo/',
    'document' => '/assets/img/document/',
    'editor_image' => '/assets/img/editor_image/',
  ],
  'tempImagePath' => storage_path() . '/temp_images/',
  'notification_page_names' => [
    'website' => 'Website',
    'home' => 'Home',
    'teams' => 'Teams',
    'matches' => 'Matches',
    'venue' => 'Venue',
    'tournament' => 'Tournament',
    'age_categories' => 'Tournament',
    'rules' => 'Tournament',
    'history' => 'Tournament',
    'program' => 'Program',
    'program_overview' => 'Program',
    'stay' => 'Stay',
    'meals' => 'Stay',
    'accommodation' => 'Stay',
    'visitors' => 'Visitors',
    'tourist_information' => 'Visitors',
    'media' => 'Media',
    'contact' => 'Contact',
  ],
  'message_notification_days' => 14,
  'activity_notification_recepients' => [
    'to' => explode(',', env('ACTIVITY_NOTIFICATION_RECEPIENTS_TO')),
    'cc' => [],
    'bcc' => explode(',', env('ACTIVITY_NOTIFICATION_RECEPIENTS_BCC')),
  ],
  'inquiries_recipient' => [
    'to' => explode(',', env('INQUIRIES_RECEPIENTS_TO')),
    'cc' => [],
    'bcc' => explode(',', env('INQUIRIES_RECEPIENTS_BCC')),
  ],
  'google_re_captcha_site_key' => env('GOOGLE_RE_CAPTCHA_SITE_KEY', ''),
  'google_re_captcha_secret_key' => env('GOOGLE_RE_CAPTCHA_SECRET_KEY', ''),
  'colorthemes' => [
		'#D0021B' => 'theme-1.css',
		'#FF9F00' => 'theme-2.css',
		'#417505' => 'theme-3.css',
		'#50E3C2' => 'theme-4.css',
		'#1E469D' => 'theme-5.css',
		'#9013FE' => 'theme-6.css',
  ],
  'parents_child_routes' => [
    'tournament' => ['tournament.page.details', 'rules.page.details', 'history.page.details', 'additional.tournament.page.details'],
    'program' => ['program.page.details', 'additional.program.page.details'],
    'stay' => ['visitor.page.details', 'tourist.page.details', 'meals.page.details', 'accommodation.page.details', 'additional.stay.page.details'],
  ],
	'font_files' => [
		'Montserrat' => '//fonts.googleapis.com/css?family=Montserrat',
		'Roboto' => '//fonts.googleapis.com/css?family=Roboto',
		'Lato' => '//fonts.googleapis.com/css?family=Lato',
		'PT Sans' => '//fonts.googleapis.com/css?family=PT+Sans',
	],
  'page_routes' => [
    'home' => 'home.page.details',
    'matches' => 'match.page.details',
    'tournament' => 'tournament.page.details',
    'age_categories' => 'tournament.page.details',
    'teams' => 'team.page.details',
    'rules' => 'rules.page.details',
    'history' => 'history.page.details',
    'program' => 'program.page.details',
    'program_overview' => 'program.page.details',
    'additional_program_page' => 'additional.program.page.details',
    'stay' => 'stay.page.details',
    'visitors' => 'visitor.page.details',
    'public_transport' => 'visitor.page.details',
    'tourist_information' => 'tourist.page.details',
    'tips' => 'visitor.page.details',
    'accommodation' => 'accommodation.page.details',
    'meals' => 'meals.page.details',
    'venue' => 'venue.page.details',
    'additional_stay_page' => 'additional.stay.page.details',
    'media' => 'media.page.details',
    'contact' => 'contact.page.details',
  ],
  'font_class' => [
    'Open Sans' => 'font-open-sans',
    'Montserrat' => 'font-montserrat',
    'Roboto' => 'font-roboto',
    'Lato' => 'font-lato',
    'PT Sans' => 'font-pt-sans',
    'Helvetica' => 'font-helvetica',
  ],
];
