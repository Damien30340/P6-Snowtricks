<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Token;
use App\Form\RegisterType;
use App\Form\ResetPasswordType;
use App\Repository\TokenRepository;
use App\Repository\UserRepository;
use App\Service\AppMailer;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class SecurityController extends AbstractController
{

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, AppMailer $mailer): Response
    {
        $user = new User();

        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $user->setToken(new Token());
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('notice', 'Inscription effectué, veuillez vérifier votre boite email !');
            $mailer->sendRegisterMail($user);
        }
        return $this->render('security/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/confirmAccount&{email}&{token}", name="app_confirm_token")
     */
    public function confirmAccount($email, $token, EntityManagerInterface $manager, UserRepository $userRepository, TokenRepository $tokenRepository, AppMailer $mailer): Response
    {
        $user = $userRepository->findOneBy(['email' => $email]);
        $tokenRepo = $tokenRepository->findOneBy(['id' => $user->getToken()]);
        if ($user->getToken()->getContent() === $token) {
            $user->setRoles(['ROLE_USER']);
            $tokenRepo
                ->setContent(null)
                ->setCreatedAt(null);
        }
        $manager->persist($user, $tokenRepo);
        $manager->flush();

        $mailer->sendConfirmAccount($user);

        return $this->render(
            'security/confirmAccount.html.twig',
            ['user' => $user]
        );
    }

    /**
     * @Route("/resetPassword", name="app_reset_password")
     */
    public function resetPassword(Request $request, AppMailer $mailer, EntityManagerInterface $manager, UserRepository $userRepository, TokenRepository $tokenRepository): Response
    {
        $user = new User();
        $form = $this->createForm(ResetPasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            ($userRepository->findOneBy(["email" => $user->getEmail()])) ? $user = $userRepository->findOneBy(["email" => $user->getEmail()]) : $this->addFlash('notice', "Aucune adresse mail connus !");
            if ($user->getToken() != null) {
                $token = $tokenRepository->findOneBy(["id" => $user->getToken()->getId()]);
                $token
                    ->setCreatedAt(new DateTime())
                    ->setContent($token->generateToken());
                $manager->persist($token);
                $manager->flush();
                $mailer->sendResetPassMail($user);
                $this->addFlash('notice', 'Veuillez cliquer sur le lien présent dans votre email !');
            }
        }
        return $this->render('security/reset_password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/responseResetPass", name="app_reset_password")
     */
    public function responsePass(Request $request, AppMailer $mailer, EntityManagerInterface $manager, UserRepository $userRepository, TokenRepository $tokenRepository): Response
    {
        $user = new User();
        $form = $this->createForm(ResetPasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            ($userRepository->findOneBy(["email" => $user->getEmail()])) ? $user = $userRepository->findOneBy(["email" => $user->getEmail()]) : $this->addFlash('notice', "Aucune adresse mail connus !");
            if ($user->getToken() != null) {
                $token = $tokenRepository->findOneBy(["id" => $user->getToken()->getId()]);
                $token
                    ->setCreatedAt(new DateTime())
                    ->setContent($token->generateToken());
                $manager->persist($token);
                $manager->flush();
                $mailer->sendResetPassMail($user);
                $this->addFlash('notice', 'Veuillez cliquer sur le lien présent dans votre email !');
            }
        }
        return $this->render('security/reset_password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/deleteAccount", name="app_delete_account")
     */
    public function deleteAccount(UserInterface $user, EntityManagerInterface $manager, AppMailer $mailer): Response
    {
        if ($user) {
            $mailer->sendDeleteAccount($user);
            $manager->remove($user);
            $manager->flush();

            return $this->redirectToRoute('app_home');
        } else {
            return $this->redirectToRoute('app_member_area');
        }
    }
}
