<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostFormType;
use App\Repository\PostRepository;
use App\Service\PostService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/author')]
class AuthorController extends AbstractController
{
    #[Route('/post/new', name: 'author_post_new')]
    public function new(Request $request, PostService $postService): Response
    {
        $this->denyAccessUnlessGranted('ROLE_AUTHOR');

        $post = new Post();
        $form = $this->createForm(PostFormType::class, $post);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $postService->createPost($post, $this->getUser());
            return $this->redirectToRoute('author_post_list');
        }

        return $this->render('author/post_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/post/{id}/edit', name: 'author_post_edit')]
    public function edit(Post $post, Request $request, PostService $postService): Response
    {
        $this-> denyAccessUnlessGranted('ROLE_AUTHOR');

        $form = $this->createForm(PostFormType::class, $post);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $postService->updatePost($post);
            return $this->redirectToRoute('author_post_list');
        }

        return $this->render('author/post_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/post/list', name: 'author_post_list')]
    public function list(PostRepository $postRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_AUTHOR');

        $posts = $postRepository->findBy(['author' => $this->getUser()]);

        return $this->render('author/post_list.html.twig', [
            'posts' => $posts,
        ]);
    }

}