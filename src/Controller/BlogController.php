<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog/{slug}", name="blog_show", requirements={"page"="\d+"})
     */
    public function show(string $slug): Response
    {
        return $this->render('/blog/show.html.twig', [
            'data' => $slug
        ]);
    }
}