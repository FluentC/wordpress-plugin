<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitd751713988987e9331980363e24189ce
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'FluentC\\Services\\' => 17,
            'FluentC\\Models\\' => 15,
            'FluentC\\Actions\\' => 16,
            'FluentC\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'FluentC\\Services\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/services',
        ),
        'FluentC\\Models\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/models',
        ),
        'FluentC\\Actions\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/actions',
        ),
        'FluentC\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitd751713988987e9331980363e24189ce::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitd751713988987e9331980363e24189ce::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitd751713988987e9331980363e24189ce::$classMap;

        }, null, ClassLoader::class);
    }
}