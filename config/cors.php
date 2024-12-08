<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'], // Add paths used by your app
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://192.168.100.36:3000', 'http://localhost:3000', 'http://localhost:3001','http://192.168.100.11:3000','http://192.168.4.99:3000','http://192.168.100.36:3001','http://192.168.153.196:3000'], // Replace with your React app's URL
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true, // If using cookies or sessions
];
