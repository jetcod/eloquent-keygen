<?php

namespace Jetcod\Eloquent\Test\Feature;

use Godruoyi\Snowflake\FileLockResolver;
use Godruoyi\Snowflake\RandomSequenceResolver;
use Godruoyi\Snowflake\SequenceResolver;
use Godruoyi\Snowflake\SnowflakeException;
use Illuminate\Filesystem\Filesystem;
use Jetcod\Eloquent\Test\TestCase;

class SequenceResolverRegistrationTest extends TestCase
{
    private string $temporaryStoragePath;

    public function setUp(): void
    {
        parent::setUp();

        $this->temporaryStoragePath = sys_get_temp_dir() . '/eloquent-keygen-' . uniqid('', true);
        $this->app->useStoragePath($this->temporaryStoragePath);
    }

    public function tearDown(): void
    {
        $this->app->make(Filesystem::class)->deleteDirectory($this->temporaryStoragePath);

        parent::tearDown();
    }

    public function testCreatesDefaultFileLockDirectoryAndRegistersResolver()
    {
        $defaultPath = $this->temporaryStoragePath . '/snowflake';

        config()->set('snowflake.attributes.sequence_resolver', FileLockResolver::class);
        config()->set('snowflake.attributes.file_lock_directory', null);

        $resolver = $this->app->make(SequenceResolver::class);

        $this->assertInstanceOf(FileLockResolver::class, $resolver);
        $this->assertDirectoryExists($defaultPath);
    }

    public function testHandlesExistingDefaultFileLockDirectory()
    {
        $defaultPath = $this->temporaryStoragePath . '/snowflake';
        $this->app->make(Filesystem::class)->ensureDirectoryExists($defaultPath, 0755, true);

        config()->set('snowflake.attributes.sequence_resolver', FileLockResolver::class);
        config()->set('snowflake.attributes.file_lock_directory', null);

        $resolver = $this->app->make(SequenceResolver::class);

        $this->assertInstanceOf(FileLockResolver::class, $resolver);
        $this->assertDirectoryExists($defaultPath);
    }

    public function testDoesNotCreateCustomFileLockDirectory()
    {
        $customPath = $this->temporaryStoragePath . '/custom-locks';

        config()->set('snowflake.attributes.sequence_resolver', FileLockResolver::class);
        config()->set('snowflake.attributes.file_lock_directory', $customPath);

        try {
            $this->app->make(SequenceResolver::class);
            $this->fail('Resolving a missing custom file-lock directory should fail.');
        } catch (SnowflakeException $exception) {
            $this->assertStringContainsString($customPath, $exception->getMessage());
        }

        $this->assertDirectoryDoesNotExist($customPath);
    }

    public function testRegistersOtherSequenceResolversWithoutCreatingDefaultDirectory()
    {
        $defaultPath = $this->temporaryStoragePath . '/snowflake';

        config()->set('snowflake.attributes.sequence_resolver', RandomSequenceResolver::class);

        $resolver = $this->app->make(SequenceResolver::class);

        $this->assertInstanceOf(RandomSequenceResolver::class, $resolver);
        $this->assertDirectoryDoesNotExist($defaultPath);
    }

    public function testRejectsInvalidSequenceResolverClass()
    {
        config()->set('snowflake.attributes.sequence_resolver', \stdClass::class);
        
        $this->expectException(\InvalidArgumentException::class);
        $this->app->make(SequenceResolver::class);
    }
}
