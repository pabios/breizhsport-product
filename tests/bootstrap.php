<?php

//use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

if ($_SERVER['APP_DEBUG']) {
    umask(0000);
}

//if (!defined('KERNEL_CLASS')) {
//    define('KERNEL_CLASS', Kernel::class); // Ajout explicite du KERNEL_CLASS
//}