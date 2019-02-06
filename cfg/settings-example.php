<?php

return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'secrettoken' => 'topsecret', // needed for authentication with JWT
        'JsonDownloadCount' => 10, // how many studies to download in the json file
        // DB Settings
        'db' => [
            'host' => 'localhost',
            'name' => 'mydbname',
            'user' => 'myuser',
            'pass' => 'mypwd'
        ]
    ],
];

?>
