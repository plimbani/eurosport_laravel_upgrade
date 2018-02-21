<?php

return [
	'website_customisation_options' => [
		'primary_colors' => [
			'#800000', '#FF0000', '#FFA500', '#FFFF00', '#808000', '#008000', '#0000FF', '#00FFFF'
		],
		'secondary_colors' => [
			'#800000', '#FF0000', '#FFA500', '#FFFF00', '#808000', '#008000', '#0000FF', '#00FFFF'
		],
		'heading_font' => [
			'Open Sans', 'Lato', 'Old Standard TT', 'Abril Fatface', 'PT Serif'
		],
		'body_font' => [
			'Ubuntu', 'Vollkorn', 'Droid', 'PT Mono', 'Gravitas One'
		]
	],
  'website_default_pages' => [
    [
      'url' => '/home',
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
  		'url' => '/venue',
  		'name' => 'venue',
      'page_name' => 'venue',
  		'title' => 'Venue',
      'content' => null,
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
      'accessible_routes' => ['venue.page.details']
  	],
  	[
  		'url' => '/tournament',
  		'name' => 'tournament',
      'page_name' => 'tournament',
  		'title' => 'Tournament',
      'content' => null,
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
      'accessible_routes' => ['tournament.page.details'],
  		'children' => [
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
  		'url' => '/program',
  		'name' => 'program',
      'page_name' => 'program',
  		'title' => 'Program',
      'content' => null,
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
      'accessible_routes' => ['program.page.details']
  	],
  	[
  		'url' => '/stay',
  		'name' => 'stay',
      'page_name' => 'stay',
  		'title' => 'Stay',
      'content' => null,
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
      'accessible_routes' => ['stay.page.details'],
  		'children' => [
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
	  		]  			
  		]
  	],
  	[
  		'url' => '/visitors',
  		'name' => 'visitors',
      'page_name' => 'visitors',
  		'title' => 'Visitors',
      'content' => null,
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
      'accessible_routes' => ['visitor.page.details'],
  		'children' => [
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
	  		]
  		]
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
  ],
  'permissions_not_changeable_pages' => ['home'],
  'current_domain' => null,
];