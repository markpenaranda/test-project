<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        // Database
        'db' => [
            'host' => 'localhost', // To change port, just add it afterwards like localhost:8889
            'dbname' => 'openday', // DB name or SQLite path
            'username' => 'root',
            // 'password' => 'openday'
            'password' => ''
        ],

        // Timezone
        'timezone' => 'UTC',
        'phpmailer' => [
                            'smtp_debug' => false,
                            'host'  => 'smtp.gmail.com', /* email host here */
                            'smtp_auth'  => true,
                            'username' => 'recruitementtools@gmail.com', /* email host here */
                            'password' => 'recruitementtools123', /* password host here */
                            'smtp_secure' => 'ssl',
                            // 'port' => 587, 
                            'port' => 465, /* port host here */
                            'sender_email' => 'no-reply@jobsglobal.com',
                            'sender_name' => 'JobsGlobal.com'
                        ]

    ],
];
