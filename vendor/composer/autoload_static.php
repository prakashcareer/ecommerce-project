<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita72e1558193cf189e0a7b1ae6b00ae88
{
    public static $prefixesPsr0 = array (
        'O' => 
        array (
            'OAuth2' => 
            array (
                0 => __DIR__ . '/..' . '/bshaffer/oauth2-server-php/src',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInita72e1558193cf189e0a7b1ae6b00ae88::$prefixesPsr0;
            $loader->classMap = ComposerStaticInita72e1558193cf189e0a7b1ae6b00ae88::$classMap;

        }, null, ClassLoader::class);
    }
}
