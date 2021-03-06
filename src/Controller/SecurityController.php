<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Token;
use App\Form\RegisterType;
use App\Service\AppMailer;
use App\Form\ForgotPasswordType;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use App\Service\Manager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, Manager $manager, UserPasswordEncoderInterface $encoder, AppMailer $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash)->setToken(new Token());
            $manager->update($user);
            $this->addFlash('notice', 'Inscription effectuée, veuillez vérifier votre boite email !');
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
     * @Route("/confirm-account/{content}", name="app_confirm_token")
     */
    public function confirmAccount(Token $token, Manager $manager, AppMailer $mailer): Response
    {
        if ($user = $token->getUser()) {
            $user->setRoles(['ROLE_USER']);
            $user->setToken(null);
        }
        $manager->delete($token);
        $manager->update($user);

        $mailer->sendConfirmAccountMail($user);

        $this->addFlash('notice', 'Votre compte est actif, veuillez vous conneter avec vos identifiants !');
        return $this->redirectToRoute('app_login');
    }

    /**
     * @Route("/forgot-password", name="app_forgot_password")
     */
    public function ForgotPassword(Request $request,Manager $manager, UserRepository $userRepository, AppMailer $mailer): Response
    {
        $form = $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // TODO voir pour refactoring
            if ($user = $userRepository->findOneBy(["email" => $form->getData()['email']])) {
                if ($user->getToken() === null) {
                    $user->setToken(new Token());
                    $manager->update($user);
                    $mailer->sendResetPassMail($user);
                    $this->addFlash('notice', 'Réinitialisation envoyée : <br>Merci de vérifier votre adresse mail');
                } else {
                    $this->addFlash('warning', 'Erreur :  <br> - Votre compte n\'est peut être pas actif.<br> - Vous avez peut-être déjà fait une demande de reinitialisation du mdp.<br><br>Merci de vérififer votre adresse mail');
                }
            } else {
                $this->addFlash('warning', "Adresse email inconnu !");
            }
            return $this->redirectToRoute('app_login');
        }
        return $this->render('security/forgot_password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/reset-password/{content}", name="app_reset_password")
     */
    public function resetPassword(Token $token, Manager $manager, UserPasswordEncoderInterface $encoder, Request $request)
    {
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $token->getUser();
            $user->setToken(null)->setPassword($encoder->encodePassword($user, $form->getData()['password']));
            $manager->delete($token);
            $manager->update($user);
            $this->addFlash('notice', 'Mot de passe modifié');
            return $this->redirectToRoute('app_login');
        }
        return $this->render('security/reset_password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/deleteAccount", name="app_delete_account")
     */
    public function deleteAccount(Manager $manager, AppMailer $mailer): Response
    {
        if ($user = $this->getUser()) {
            $mailer->sendDeleteAccountMail($user);
            $manager->delete($user);

            $this->container->get('security.token_storage')->setToken(null);
            return $this->redirectToRoute('app_logout');
        } else {
            return $this->redirectToRoute('app_member_area');
        }
    }
}
