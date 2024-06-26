<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0a3ae17e81e26cf8e6a1942d232b2a36
{
    public static $files = array (
        '6268d3d0829d4d1be730eda85c6eb059' => __DIR__ . '/../..' . '/includes/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'WpPool\\Store\\Order\\' => 15,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'WpPool\\Store\\Order\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit0a3ae17e81e26cf8e6a1942d232b2a36::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit0a3ae17e81e26cf8e6a1942d232b2a36::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit0a3ae17e81e26cf8e6a1942d232b2a36::$classMap;

        }, null, ClassLoader::class);
    }
}
