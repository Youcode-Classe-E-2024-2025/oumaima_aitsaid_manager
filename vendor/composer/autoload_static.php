<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit0731d1d8c2b36b28af09a76385cdc5e1
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
        'M' => 
        array (
            'MathPHP\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
        'MathPHP\\' => 
        array (
            0 => __DIR__ . '/..' . '/markrogoyski/math-php/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit0731d1d8c2b36b28af09a76385cdc5e1::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit0731d1d8c2b36b28af09a76385cdc5e1::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit0731d1d8c2b36b28af09a76385cdc5e1::$classMap;

        }, null, ClassLoader::class);
    }
}
