<?php
return [

    // FORMAT EXAMPLE.
    //
    // 'extension_tracker' => [
    //     'key' => 'extension_tracker',
    //     'wiki_key' => 'Extension_Tracker',
    //     'creators' => json_encode([
    //         'Uri' => 'https://github.com/preimpression/',
    //     ]),
    //     'version' => '1.0.0',
    // ],
    
    'dailies' => [
        'key' => 'dailies',
        'wiki_key' => 'Dailies',
        'creators' => json_encode([
            'Cylunny' => 'https://toyhou.se/cylunny',
        ]),
        'version' => '2.0.1', 
    ],



    'theme_manager' => [
        'key' => 'theme_manager',
        'wiki_key' => 'Theme Manager',
        'creators' => json_encode([
            'Uri' => 'https://github.com/preimpression/',
            'Cylunny' => 'https://toyhou.se/cylunny',
            'moif' => 'https://toyhou.se/moif'
        ]),
        'version' => '2.0.0', // Big update with Cylunny's Theme manager + Moif's work merging the two!
    ],
   
    'world_expansion' => [
        'key' => 'world_expansion',
        'wiki_key' => 'World_Expansion',
        'creators' => json_encode([
            'Uri' => 'https://github.com/preimpression/',
            'Mercury' => 'https://github.com/itinerare/',
        ]),
        'version' => '1.3.2',
    ],
	
    'liveclock' => [
         'key' => 'liveclock',
         'wiki_key' => 'LiveClock',
         'creators' => json_encode([
            'Speedy' => 'https://github.com/SpeedyD/',
         ]),
         'version' => '1.0.2',
     ],

    
    'alternate_site_designs' => [
        'key' => 'alternate_site_designs',
        'wiki_key' => 'Alternate Site Designs',
        'creators' => json_encode([
            'Cylunny' => 'https://toyhou.se/cylunny',
        ]),
        'version' => '1.0.0', 
    ],

];
