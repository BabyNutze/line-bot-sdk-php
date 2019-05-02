<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0b7a884e037960efa5063585f0143af2
{
    public static $prefixLengthsPsr4 = array (
        'L' => 
        array (
            'LINE\\' => 5,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'LINE\\' => 
        array (
            0 => __DIR__ . '/..' . '/linecorp/line-bot-sdk/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit0b7a884e037960efa5063585f0143af2::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit0b7a884e037960efa5063585f0143af2::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
