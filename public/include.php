<?php
/*
 * This file is part of the evo package.
 *
 * (c) John Andrew <simplygenius78@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);


/**
 * Root constants required for the main index file
 */

defined('ROOT_PATH') or
define('ROOT_PATH', realpath(dirname(__FILE__, 2)));
//echo ROOT_PATH;

/**
 * Load the composer library
 */
$autoload = ROOT_PATH . '/vendor/autoload.php';
if (is_file($autoload)) {
    require $autoload;
}