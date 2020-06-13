<?php

declare(strict_types=1);

/**
 * @link https://flextype.org
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Flextype;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Slim\Http\Environment;
use Slim\Http\Uri;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Flextype\Component\Filesystem\Filesystem;
use Flextype\Component\Session\Session;
use Flextype\Component\Arr\Arr;

class ContactController extends Container
{
    /**
     * Proccess contact form and send
     *
     * @param Request  $request  PSR7 request
     * @param Response $response PSR7 response
     * @param array    $args     Args
     */
    public function contactProcess(Request $request, Response $response, array $args) : Response
    {
        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);

        // Get Data from POST
        $post_data = $request->getParsedBody();

        Arr::delete($post_data, 'csrf_name');
        Arr::delete($post_data, 'csrf_value');
        Arr::delete($post_data, 'form-save-action');

        // From:
        $mail->setFrom('awilum@yandex.ru', 'Mailer');

        // To:
        $mail->addAddress('awilum@yandex.ru', 'Test');

        // Content
        $mail->isHTML(true);

        // Get subject from POST DATA or set DEFAULT from contact settings
        if (isset($post_data['subject'])) {
            $mail->Subject = $post_data['subject'];
            Arr::delete($post_data, 'subject');
        } else {
            $mail->Subject = $this->registry->get('plugins.contact.settings.default_subject');
        }

        // Get all fields data
        $data = '';
        foreach ($post_data as $key => $value) {
            $data .= '<b>' . $key . '</b>' . ': ' . $value . '<br>';
        }

        // Add email body
        $mail->Body = $data;

        // Send email
        $mail->send();

        $this->flash->addMessage('success', 'Send');

        return $response->withRedirect('.');
    }
}
