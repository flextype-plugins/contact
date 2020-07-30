<?php

declare(strict_types=1);

/**
 * @link https://flextype.org
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Flextype\Plugin\Contact\Controllers;

use Ramsey\Uuid\Uuid;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Slim\Http\Environment;
use Slim\Http\Uri;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Flextype\Component\Filesystem\Filesystem;
use Flextype\Component\Session\Session;
use Flextype\Component\Arrays\Arrays;

use Flextype\App\Foundation\Container;

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

        Arrays::delete($post_data, 'csrf_name');
        Arrays::delete($post_data, 'csrf_value');
        Arrays::delete($post_data, 'form-save-action');

        if (Arrays::keyExists($post_data, 'mailbox')) {
            $mailbox = $post_data['mailbox'];
        } else {
            $mailbox = 'default';
        }

        Arrays::delete($post_data, 'mailbox');

        $post_data['uuid'] = Uuid::uuid4()->toString();
        $post_data['created_at'] = (string) date($this->registry->get('flextype.settings.date_format'), time());

        if (Filesystem::has(PATH['project'] . '/mailboxes/' . $mailbox)) {
            Filesystem::createDir(PATH['project'] . '/mailboxes/' . $mailbox . '/' . $post_data['uuid']);
            Filesystem::write(PATH['project'] . '/mailboxes/' . $mailbox . '/' .  $post_data['uuid'] . '/message.yaml', $this->serializer->encode($post_data, 'yaml'));
        } else {
            Filesystem::createDir(PATH['project'] . '/mailboxes/' . $mailbox . '/' . $post_data['uuid']);
            Filesystem::write(PATH['project'] . '/mailboxes/' . $mailbox . '/' .  $post_data['uuid'] . '/message.yaml', $this->serializer->encode($post_data, 'yaml'));
        }

        // From:
        $mail->setFrom($this->registry->get('plugins.contact.settings.from.email'), $this->registry->get('plugins.contact.settings.from.name'));

        // To:
        $mail->addAddress($this->registry->get('plugins.contact.settings.to.email'), $this->registry->get('plugins.contact.settings.to.name'));

        // Content
        $mail->isHTML(true);

        // Get subject from POST DATA or set DEFAULT from contact settings
        if (isset($post_data['subject'])) {
            $mail->Subject = $post_data['subject'];
            Arrays::delete($post_data, 'subject');
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

        $this->flash->addMessage('success', $this->registry->get('plugins.contact.settings.message_success'));

        return $response->withRedirect('.');
    }
}
