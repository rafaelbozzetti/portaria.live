<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite62fec2b9f0f33760b5996128a28e070
{
    public static $prefixLengthsPsr4 = array (
        'L' => 
        array (
            'League\\Csv\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'League\\Csv\\' => 
        array (
            0 => __DIR__ . '/..' . '/league/csv/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInite62fec2b9f0f33760b5996128a28e070::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInite62fec2b9f0f33760b5996128a28e070::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
