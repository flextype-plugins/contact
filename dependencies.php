<?php

declare(strict_types=1);

/**
 * @link http://digital.flextype.org
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Flextype\Plugin\Contact;

use Flextype\Plugin\Contact\Controllers\ContactController;

$flextype['ContactController'] = static function ($container) {
    return new ContactController($container);
};
