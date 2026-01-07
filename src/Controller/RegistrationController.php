<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Uid\Uuid;
use App\Security\AppAuthenticator;

class RegistrationController extends AbstractController
{
    // Route de la page d'inscription
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Hash du mot de passe
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // Génération du token de confirmation email
            $token = Uuid::v4()->toRfc4122();
            $user->setEmailVerificationToken($token);
            $user->setIsVerified(false);

            $entityManager->persist($user);
            $entityManager->flush();

            // Création du lien de confirmation
            $confirmationUrl = $this->generateUrl(
                'app_verify_email',
                ['token' => $token],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            // Envoi du mail de confirmation
            $email = (new Email())
                ->from('no-reply@stubborn.fr')
                ->to($user->getEmail())
                ->subject('Confirmation de votre inscription')
                ->html("<p>Bonjour {$user->getUsername()},</p><p>Veuillez confirmer votre inscription en cliquant sur le lien ci-dessous :</p><p><a href='{$confirmationUrl}'>Confirmer mon inscription</a></p>");

            $mailer->send($email);

            $this->addFlash('success', 'Un email de confirmation vous a été envoyé.');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('register/index.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/verify-email/{token}', name: 'app_verify_email')]
    public function verifyEmail(
        string $token,
        EntityManagerInterface $em,
        Security $security,
        Request $request
    ): Response {
        $user = $em->getRepository(User::class)->findOneBy([
            'emailVerificationToken' => $token
        ]);

        if (!$user) {
            $this->addFlash('warning', 'Ce lien est invalide ou a déjà été utilisé.');
            return $this->redirectToRoute('app_login');
        }

        // Marque l'utilisateur comme vérifié
        $user->setIsVerified(true);
        $user->setEmailVerificationToken(null);
        $em->flush();

        // Connection automatique
        $security->login(
            $user,
            AppAuthenticator::class,
            'main'
        );

        $this->addFlash('success', 'Votre email a été confirmé. Bienvenue !');

        return $this->redirectToRoute('app_home');
    }
}
