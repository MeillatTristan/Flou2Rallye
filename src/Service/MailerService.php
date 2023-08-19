<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Qferrer\Mjml\Twig\MjmlExtension;
use Twig\Environment;
use Mailjet\Resources;
use Mailjet\Client;

class MailerService
{
    private ?Client $mj;
    private $mailer;
    protected MjmlExtension $mjmlRenderer;
    protected Environment $twig;

    public function __construct(MailerInterface $mailer, MjmlExtension $mjmlRenderer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->mjmlRenderer = $mjmlRenderer;
        $this->twig = $twig;
        $this->mj = null;
    }

    public function sendEmail(string $from, string $to, string $subject, string $templatePath, array $templateData = [])
    {

        $this->mj = new \Mailjet\Client("75f5ee34751956e0abee23848a337c61", "1ff8df400604be3153c9bb3020f9d56b", true, ['version' => 'v3.1']);
        
        $htmlContent = $this->twig->render($templatePath, $templateData);

        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => $from,
                    ],
                    'To' => [
                        [
                            'Email' => $to,
                        ]
                    ],
                    'Subject' => $subject,
                    'HTMLPart' => $htmlContent,

                    // Si activé, fait les appels api mais n'envoie pas les mails
                    //'SandboxMode' => true,
                ]
            ]
        ];

        $response = $this->mj->post(Resources::$Email, ['body' => $body]);

        if (!$response->success()) {
            return "ERROR_SENDING";
        } else {
            return "SUCCESS_SENDING";
        }

    }
}

