<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */
    'default' => 'mysql',

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Resta is shown below to make development simple.
    |
    |
    | All database work in Resta is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */
    'connections' => [
        'mysql' => [
            'driver'    => environment('db_connection','mysql'),
            'port'      => environment('db_port','localhost'),
            'host'      => environment('db_host','localhost'),
            'database'  => environment('db_database','database'),
            'user'      => environment('db_username','user'),
            'password'  => environment('db_password','password'),
        ]
    ]

];