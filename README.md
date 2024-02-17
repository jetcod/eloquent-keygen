# Eloquent KeyGen

This is a Laravel package providing seamless integration with Snowflake ID generation for Eloquent models. Simplify primary key management with automatic generation using a highly distributed unique identifier system. Enhance your application's scalability and efficiency with this easy-to-use package for Laravel Eloquent models.

## Installation

To install `jetcod/deloquent-keygen`, you can use Composer, the dependency manager for PHP. Run the following command in your terminal:

```sh
composer require jetcod/eloquent-keygen
```

Then you need to publish the config file:

```sh
php artisan vendor:publish --provider="eloquent-key-generator-config"
```

## How to use?

To use the package, follow these steps:

- **Extend Your Model**:

Instead of extending Illuminate\Database\Eloquent\Model, extend Jetcod\Eloquent\Model in your Eloquent model. This will enable automatic ID generation using the Snowflake algorithm.

```php
<?php

namespace App\Models;

use Jetcod\Eloquent\Model as EloquentModel;

class YourModel extends EloquentModel
{
    // Your model code here
}
```

- **Disable Snowflake Generation for a Model**:

If you want to disable the Snowflake ID generation for a particular model, you can add a snowflake method to that model returning false.

```php
<?php

namespace App\Models;

use Jetcod\Eloquent\Model as EloquentModel;

class AnotherModel extends EloquentModel
{
    protected function snowflake(): bool
    {
        return false;
    }

    // Your model code here
}
```

The snowflake method returns a boolean value indicating whether the Snowflake ID generation should be enabled or disabled for that model.

- **Configuration for distributed system**:

The package uses the following configuration to generate the distributed unique identifier:

```php
<?php

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
```

## License

This project is licensed under the MIT License - see the [LICENSE](./LICENSE) file for details.
