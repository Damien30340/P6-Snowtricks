<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Token;
use App\Form\RegisterType;
use App\Service\AppMailer;
use App\Service\TokenManager;
use App\Form\ForgotPasswordType;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{

    /**
     * @Route("/inscription", name="app_register")
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
     * @Route("/connexion", name="app_login")
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
     * @Route("/confirmation-compte&{id}&{token}", name="app_confirm_token")
     */
    public function confirmAccount($id, $token, TokenManager $tokenManager, EntityManagerInterface $manager, UserRepository $userRepository, AppMailer $mailer): Response
    {
        $user = $userRepository->findOneBy(['id' => $id]);
        if ($user->getToken()->getContent() === $token) {
            $user->setRoles(['ROLE_USER']);
            $tokenManager->nullableToken($user);
        }
        $manager->persist($user, $tokenManager);
        $manager->flush();

        $mailer->sendConfirmAccountMail($user);

        return $this->render(
            'security/confirmAccount.html.twig',
            ['user' => $user]
        );
    }

    /**
     * @Route("/mdp-oublie", name="app_forgot_password")
     */
    public function ForgotPassword(Request $request, AppMailer $mailer, TokenManager $token, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(ForgotPasswordType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($userRepository->findOneBy(["email" => $user->getEmail()])) {
                $user = $userRepository->findOneBy(["email" => $user->getEmail()]);
                if ($user->getToken()->getContent() === null && $user->getToken()->getCreatedAt() === null) {
                    $token->generateTokenResetPass($user);
                    $mailer->sendResetPassMail($user);
                    $this->addFlash('notice', 'Réinitialisation envoyée : <br>Merci de vérifier votre adresse mail');
                } else {
                    $this->addFlash('warning', 'Erreur :  <br> - Votre compte n\'est peut être pas actif.<br> - Vous avez peut-être déjà fait une demande de reinitialisation du mdp.<br><br>Merci de vérififer votre adresse mail');
                }
            } else {
                $this->addFlash('warning', "Adresse email inconnu !");
            }
        }
        return $this->render('security/forgot_password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/mdp-reinitialise&{id}&{token}", name="app_reset_password")
     */
    public function ResetPassword($id, $token, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, Request $request, UserRepository $userRepository, TokenManager $tokenManager)
    {
        if ($userRepository->findOneBy(["id" => $id])) {
            $user = $userRepository->findOneBy(["id" => $id]);
            if ($user->getToken()->getContent()) {
                $form = $this->createForm(ResetPasswordType::class, $user);
                $form->handleRequest($request);
                if ($form->isSubmitted()) {
                    if ($user->getToken()->getContent() === $token) {
                        $tokenManager->nullableToken($user);
                        $hash = $encoder->encodePassword($user, $user->getPassword());
                        $user->setPassword($hash);
                        $manager->persist($user);
                        $manager->flush();
                        $this->addFlash('notice', 'Mot de passe modifié');
                    } else {
                        $this->addFlash('warning', 'Erreur : Les jetons ne correspondent pas !!!');
                    }
                }
                return $this->render('security/reset_password.html.twig', [
                    'form' => $form->createView()
                ]);
            } else {
                $this->addFlash('warning', 'Erreur : Le lien n\'est pas valide !');
                return $this->render('security/reset_password.html.twig');
            }
        } else {
            $this->addFlash('warning', 'Erreur : aucun compte corréspondant !!!');
            return $this->render('security/reset_password.html.twig');
        }
    }

    /**
     * @Route("/suprime-compte", name="app_delete_account")
     */
    public function deleteAccount(UserInterface $user, EntityManagerInterface $manager, AppMailer $mailer): Response
    {
        if ($user) {
            $mailer->sendDeleteAccountMail($user);
            $manager->remove($user);
            $manager->flush();

            return $this->redirectToRoute('app_home');
        } else {
            return $this->redirectToRoute('app_member_area');
        }
    }
}
