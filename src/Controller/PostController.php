<?php

namespace App\Controller;

use App\Entity\Post;
use App\Service\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;

#[Route('/post')]
class PostController extends AbstractController
{
    #[Route('/list', name: 'post_list')]
    public function index(PostService $postService): Response
    {
        $posts = $postService->getPublishedPosts();

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/{slug}', name: 'post_show')]
    public function show(
        #[MapEntity(mapping: ['slug' => 'slug'])] Post $post
    ): Response {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }
}

