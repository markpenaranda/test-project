<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5fa9b12b2a65262f48f9f52a437a306c
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit5fa9b12b2a65262f48f9f52a437a306c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit5fa9b12b2a65262f48f9f52a437a306c::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}