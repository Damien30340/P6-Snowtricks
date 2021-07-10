<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class ApiController
 * @package App\Controller
 * @Route("/api")
 */
class ApiController extends AbstractController
{
    /**
     * @Route("/tricks", name="api_trick")
     * @param TrickRepository $trickRepository
     * @return Response
     */
    public function index(TrickRepository $trickRepository, Request $request): Response
    {
        $page = $request->get('page', 1);

        $tricks = $trickRepository->getLoadMoreTrick($page);
        $pictures = [];
        $categories = [];
        $comments = [];

        for ($i = 0; $i < count($tricks); $i++){
            $comment = strval(count($tricks[$i]->getComments()));
            $category = $tricks[$i]->getCategory()->getName();
            $picture = $tricks[$i]->getDefaultPicture();
            array_push($categories, $category);
            array_push($pictures, $picture);
            array_push($comments, $comment);
        }

        $totalTricks = $trickRepository->getTotalTricks() - 6;
        $totalPages = ceil($totalTricks / 6 + 1);

        if($this->getUser() === null){
            $user = false;
        } else {
            $user = true;
        }

        return $this->json([
            'connected' => $user,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'categories' => $categories,
            'pictures' => $pictures,
            'tricks' => $tricks,
            'comments' => $comments
        ], 200, [], [
            'groups' => ['loadMore']
        ]);

    }


}
