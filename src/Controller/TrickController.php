<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Form\TrickFormType;
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
     * @Route("/show/{id}", name="app_show_trick")
     * @param Trick $trick
     * @return Response
     */
    public function trickShow(Trick $trick): Response
    {
        $picture = $trick->getDefaultPicture();

        return $this->render('trick/trick.html.twig', [
            'trick' => $trick,
            'picture' => $picture,
        ]);
    }

    /**
     * @Route("/add", name="app_trick_add")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function trickAdd(Request $request, EntityManagerInterface $em): Response
    {
        $trick = new trick();
        $form = $this->createForm(TrickFormType::class, $trick)->handleRequest($request);


        if($form->isSubmitted() && $form->isValid())
        {
            /** @var Trick $trick */
            $trick = $form->getData();
            $em->persist($trick);
            $em->flush();
            $this->addFlash("success", 'Figure créée');
            return $this->redirectToRoute("app_show_trick",['id' => $trick->getId()]);
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
        $form = $this->createForm(TrickFormType::class, $trick)->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $trick = $form->getData();
            $em->persist($trick);
            $em->flush();
            $this->addFlash("success", 'Figure modifiée');
            return $this->redirectToRoute("app_show_trick", ['id' => $trick->getId()]);
        }
        return $this->render('trick/edit.html.twig', [
            'form' => $form->createView(),
            'picture' => $trick->getDefaultPicture()
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
