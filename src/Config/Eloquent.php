<?php

/*
 |----------------------------------------------------------------------------------------
 | Custom Library Configuration
 |----------------------------------------------------------------------------------------
 | This configuration file is used to define settings for the Eloquent KeyGen lib library.
 | It provides the ability to configure the primary key generator class.
 |
 */

return [

    'customModel' => [
        /*
         |------------------------------------------------------------
         | Specify the class to be used as the primary key generator.
         |------------------------------------------------------------
         */

        'generator' => \Jetcod\Eloquent\PrimaryKeyGenerator::class,
    ],
];
