<?php

declare(strict_types=1);

/**
 * @link https://flextype.org
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Flextype\Plugin\Contact;

use function is_file;

/**
 * Ensure vendor libraries exist
 */
! is_file($contant_autoload = __DIR__ . '/vendor/autoload.php') and exit('Please run: <i>composer install</i> for contact plugin');

/**
 * Register The Auto Loader
 *
 * Composer provides a convenient, automatically generated class loader for
 * our application. We just need to utilize it! We'll simply require it
 * into the script here so that we don't have to worry about manual
 * loading any of our classes later on. It feels nice to relax.
 * Register The Auto Loader
 */
$contact_loader = require_once $contant_autoload;

/**
 * Include web routes
 */
include_once 'routes/web.php';
