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
    'attributes' => [
        /*
        |------------------------------------------------------------------
        | This represents the datacenter id used to generate snowflake ids.
        |------------------------------------------------------------------
        */

        'datacenter' => env('SNOWFLAKE_DATACENTER_ID', 1),

        /*
        |--------------------------------------------------------------
        | This represents the worker id used to generate snowflake ids.
        |--------------------------------------------------------------
        */

        'worker' => env('SNOWFLAKE_WORKER_ID', 1),

        /*
        |--------------------------------------------------------------------------------------------------------
        | This represents the sequence resolver class. It is to ensure that sequence-number generated in the same
        | millisecond of the same node is unique.
        |
        | Avaialable resolvers:
        |   1. Godruoyi\Snowflake\RandomSequenceResolver
        |   2. Godruoyi\Snowflake\FileLockResolver
        |   3. Godruoyi\Snowflake\LaravelSequenceResolver
        |--------------------------------------------------------------------------------------------------------
        */

        'sequence_resolver' => env('SNOWFLAKE_SEQUENCE_RESOLVER', LaravelSequenceResolver::class),

        'file_lock_directory' => env('SNOWFLAKE_File_LOCK_DIRECTORY', null),  // Default is null, means use <app_path>/storage/snowflake directory.
    ],
];
