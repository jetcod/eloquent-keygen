<?php

/*
 |------------------------------------------------------------------------------------
 | Custom Library Configuration
 |------------------------------------------------------------------------------------
 | This configuration file is used to define configuration for snowflake algorithm
 | which used to generate primary keys.
 |
 */

use Godruoyi\Snowflake\LaravelSequenceResolver;

return [
    'snowflake' => [
        /*
        |------------------------------------------------------------------
        | This represents the datacenter id used to generate snowflake ids.
        |------------------------------------------------------------------
        */

        'datacenter' => env('DATACENTER_ID', 1),

        /*
        |--------------------------------------------------------------
        | This represents the worker id used to generate snowflake ids.
        |--------------------------------------------------------------
        */

        'worker' => env('WORKER_ID', 1),

        /*
        |--------------------------------------------------------------------------------------------------------
        | This represents the sequence resolver class. It is to ensure that sequence-number generated in the same
        | millisecond of the same node is unique.
        |--------------------------------------------------------------------------------------------------------
        */

        'sequence_resolver' => LaravelSequenceResolver::class,
    ],
];
