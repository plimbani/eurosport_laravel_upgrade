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
      'slug' => 'home',
      'name' => 'home',
      'title' => 'Home',
      'is_enabled' => 1,
      'is_published' => 1,
      'is_permission_changeable' => 0,
    ],
  	[
  		'slug' => 'teams',
  		'name' => 'teams',
  		'title' => 'Teams',
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
  	],
  	[
  		'slug' => 'matches',
  		'name' => 'matches',
  		'title' => 'Matches',
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
  	],
  	[
  		'slug' => 'venue',
  		'name' => 'venue',
  		'title' => 'Venue',
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
  	],
  	[
  		'slug' => 'tournament',
  		'name' => 'tournament',
  		'title' => 'Tournament',
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
  		'children' => [
  			[
  				'slug' => 'rules',
	  			'name' => 'rules',
	  			'title' => 'Rules',
          'is_enabled' => 0,
          'is_published' => 0,
          'is_permission_changeable' => 1,
	  		],
  			[
  				'slug' => 'history',
	  			'name' => 'history',
	  			'title' => 'History',
          'is_enabled' => 0,
          'is_published' => 0,
          'is_permission_changeable' => 1,
	  		],		  			
  		]
  	],
  	[
  		'slug' => 'program',
  		'name' => 'program',
  		'title' => 'Program',
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
  	],
  	[
  		'slug' => 'stay',
  		'name' => 'stay',
  		'title' => 'Stay',
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
  		'children' => [
  			[
  				'slug' => 'meals',
	  			'name' => 'meals',
	  			'title' => 'Meals',
          'is_enabled' => 0,
          'is_published' => 0,
          'is_permission_changeable' => 1,
	  		],
  			[
  				'slug' => 'accommodation',
	  			'name' => 'accommodation',
	  			'title' => 'Accommodation',
          'is_enabled' => 0,
          'is_published' => 0,
          'is_permission_changeable' => 1,
	  		]  			
  		]
  	],
  	[
  		'slug' => 'visitors',
  		'name' => 'visitors',
  		'title' => 'Visitors',
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
  		'children' => [
  			[
  				'slug' => 'tourist-information',
	  			'name' => 'tourist_information',
	  			'title' => 'Tourist information',
          'is_enabled' => 0,
          'is_published' => 0,
          'is_permission_changeable' => 1,
	  		]
  		]
  	],
    [
      'slug' => 'media',
      'name' => 'media',
      'title' => 'Media',
      'is_enabled' => 0,
      'is_published' => 0,
      'is_permission_changeable' => 1,
    ],
  ],
  'permissions_not_changeable_pages' => ['home'],
];