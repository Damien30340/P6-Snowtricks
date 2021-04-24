<?php

namespace App\Controller;

use App\Form\UpdateMemberType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Require ROLE_USER for *every* controller method in this class.
 *
 * @IsGranted("ROLE_USER")
 */

class MemberAreaController extends AbstractController
{
    /**
     * @Route("/espace-membre", name="app_member_area")
     */
    public function index(Request $request, EntityManagerInterface $manager, UserInterface $user): Response
    {
        $user->getUsername();
        $form = $this->createForm(UpdateMemberType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
        }


        return $this->render('member_area/index.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }
}
