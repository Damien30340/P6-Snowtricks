<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommentController
 * @package App\Controller
 * @Route ("/comment")
 */
class CommentController extends AbstractController
{
    /**
     * @Route("/delete/{id}", name="comment")
     */
    public function index(Comment $comment, EntityManagerInterface $em): Response
    {
        $trickId = $comment->getTrick()->getId();
        $em->remove($comment);
        $em->flush();
        $this->addFlash('success', "Votre commentaire vient d'être supprimé !");
        return $this->redirectToRoute("app_show_trick", ['id' => $trickId]);
    }
}
