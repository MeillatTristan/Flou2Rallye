<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Qferrer\Mjml\Twig\MjmlExtension;
use Twig\Environment;

class MailerService
{
    private $mailer;
    protected MjmlExtension $mjmlRenderer;
    protected Environment $twig;

    public function __construct(MailerInterface $mailer, MjmlExtension $mjmlRenderer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->mjmlRenderer = $mjmlRenderer;
        $this->twig = $twig;
    }

    public function sendEmail(string $from, string $to, string $subject, string $templatePath, array $templateData = []): void
    {
        
        $htmlContent = $this->twig->render($templatePath, $templateData);

        $email = (new Email())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->html($htmlContent);

        $this->mailer->send($email);
    }
}

