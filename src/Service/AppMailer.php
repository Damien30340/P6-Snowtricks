<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class AppMailer
{

    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }


    public function sendRegisterMail(User $user)
    {
        $email = (new Email())
            ->from('contact@damiengobert.fr')
            ->to($user->getEmail())
            ->subject('Vérification compte : ' . $user->getUsername())
            ->html('<p>
        Inscription ! 
        <br>
        Le compte nécessite une activation.
        <br>
        Informations de l\'utilisateur enregistré :
        <br><br>
        Votre pseudo :
        ' . $user->getUsername() . '
        <br>
        Email :
        ' . $user->getEmail() . '
        <br>
        Password : Celui que vous avez renseigné lors de votre inscription !
        <br><br>
        Cliquez sur le lien suivant pour activer votre compte : <a href="http://localhost:8000/confirmation-compte/' . $user->getToken()->getContent() . '">http://localhost:8000/confirmation-compte/' . $user->getToken()->getContent() . '</a>
        <br>Token édité le ' . $user->getToken()->getCreatedAt()->format('d-m-Y') . ' </p>');

        $this->mailer->send($email);
    }

    public function sendConfirmAccountMail(User $user)
    {
        $email = (new Email())
            ->from('contact@damiengobert.fr')
            ->to($user->getEmail())
            ->subject('Activation de votre compte : ' . $user->getUsername())
            ->html('<p>
        Votre compte a bien été activé ! 
        <br><br>
        <a href="http://localhost:8000/>http://Snowtricks/Symfony/</a></p>');

        $this->mailer->send($email);
    }

    public function sendResetPassMail(User $user)
    {
        $email = (new Email())
            ->from('contact@damiengobert.fr')
            ->to($user->getEmail())
            ->subject('Récupération de mot de passe : ' . $user->getUsername())
            ->html('<p>
        Récupération de votre mot de passe ! 
        <br><br>
        Bonjour, ' . $user->getUsername() . ', une demande de récupération de mot de passe à été demandé, si vous n\'êtes pas à l\'initiave de celle ci, ne cliquez pas sur ce lien. 
        <br><br>
        Lien de réinitialisation : <a href="http://localhost:8000/mdp-reinitialise/' . $user->getToken()->getContent() . '">http://localhost:8000/mdp-reinitialise/' . $user->getToken()->getContent() . '</a>');

        $this->mailer->send($email);
    }

    public function sendDeleteAccountMail(User $user)
    {
        $email = (new Email())
            ->from('contact@damiengobert.fr')
            ->to($user->getEmail())
            ->subject('Suppression de votre compte : ' . $user->getUsername())
            ->html('<p>
        Votre compte a bien été supprimé ! 
        <br><br>
        Aurevoir, nous esperons à bientôt !');

        $this->mailer->send($email);
    }
}
