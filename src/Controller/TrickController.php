<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentFormType;
use App\Form\TrickFormType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TrickController
 * @package App\Controller
 * @Route("/trick")
 */
class TrickController extends AbstractController
{

    /**
     * @Route("/show/{id}/{slug}", name="app_show_trick")
     * @param Trick $trick
     * @return Response
     */
    public function trickShow(Trick $trick, CommentRepository $repo, Request $request, EntityManagerInterface $em): Response
    {
        $comment = new Comment();
        $commentForm = $this->createForm(CommentFormType::class, $comment)->handleRequest($request);

        if($commentForm->isSubmitted() && $commentForm->isValid())
        {
            $comment->setAuthor($this->getUser())
                    ->setTrick($trick);
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('app_show_trick', ['id' => $trick->getId()]);
        }
        $picture = $trick->getDefaultPicture();

        $page = (int)$request->query->get("page", 1);
        $limit = 10;
        $totalComments = count($trick->getComments());
        $comments = $repo->getPaginatedComments($page, $limit, $trick);

        return $this->render('trick/trick.html.twig', [
            'trick' => $trick,
            'comments' => $comments,
            'page' => $page,
            'limit' => $limit,
            'totalComments' => $totalComments,
            'picture' => $picture,
            'commentForm' => $commentForm->createView()
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/add", name="app_trick_add")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function trickAdd(Request $request, EntityManagerInterface $em): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickFormType::class, $trick)->handleRequest($request);


        if($form->isSubmitted() && $form->isValid())
        {
            /** @var Trick $trick */
            $trick = $form->getData();
            $em->persist($trick);
            $em->flush();
            $this->addFlash("success", 'Figure créée');
            return $this->redirectToRoute("app_show_trick",['id' => $trick->getId(), 'slug' => $trick->getSlug()]);
        }
        return $this->render('trick/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/edit/{id}", name="app_trick_edit")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param Trick $trick
     * @return Response
     */
    public function trickEdit(Request $request, EntityManagerInterface $em, Trick $trick): Response
    {
        dump($trick->getPictures());
        $form = $this->createForm(TrickFormType::class, $trick)->handleRequest($request);
        dump($trick);

        if($form->isSubmitted() && $form->isValid()){
            $trick = $form->getData();
            $em->persist($trick);
            $em->flush();
            $this->addFlash("success", 'Figure modifiée');
            return $this->redirectToRoute("app_show_trick", ['id' => $trick->getId(), 'slug' => $trick->getSlug()]);
        }
        return $this->render('trick/edit.html.twig', [
            'form' => $form->createView(),
            'picture' => $trick->getDefaultPicture(),
            'trick' => $trick
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/delete/{id}", name="app_trick_delete")
     * @param Trick $trick
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function trickDelete(Trick $trick, EntityManagerInterface $em): Response
    {
        $em->remove($trick);
        $em->flush();
        $this->addFlash("success", "La figure vient d'être supprimé !");
        return $this->redirectToRoute("app_home");
    }
}
