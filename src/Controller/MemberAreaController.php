<?php

namespace App\Controller;

use App\Form\UpdateMemberType;
use App\Service\Manager;
use App\Service\Uploader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
     * @Route("/member_area", name="app_member_area")
     */
    public function index(Request $request, Manager $manager, Uploader $uploader): Response
    {
        $form = $this->createForm(UpdateMemberType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getUser()->setAvatar($uploader->upload($form['avatar']->getData(), Uploader::AVATAR_DIR ));
            $manager->update($this->getUser());
            return $this->redirectToRoute('app_member_area');
        }


        return $this->render('member_area/member_area.html.twig', [
            'user' => $this->getUser(),
            'form' => $form->createView()
        ]);
    }
}
