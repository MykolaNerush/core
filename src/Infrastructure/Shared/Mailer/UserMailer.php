<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Mailer;

use App\Domain\Core\User\Entity\User;
use App\Infrastructure\Shared\Services\User\UserConfirmationService;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final readonly class UserMailer
{
    public function __construct(
        private MailerInterface         $mailer,
        private UrlGeneratorInterface   $urlGenerator,
        private UserConfirmationService $UserConfirmationService,
        private string                  $secretKey
    )
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendConfirmationEmail(User $user): void
    {
        $plainToken = $this->UserConfirmationService->generateConfirmationToken($user);
        $encodedEmail = urlencode($user->getEmail());
        $signature = hash_hmac('sha256', $user->getEmail(), $this->secretKey);
        $confirmationUrl = $this->urlGenerator->generate(
            'confirm_email',
            [
                'email' => $encodedEmail,
                'token' => $plainToken,
                'signature' => $signature,
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $email = (new Email())
            ->from('no-reply@yourapp.com')
            ->to($user->getEmail())
            ->subject('Confirm your email')
            ->html('<p>Please confirm your email by clicking <a href="' . $confirmationUrl . '">this link</a>.</p>');
        $this->mailer->send($email);
    }
}
