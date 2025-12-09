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
    #[Route('/post/list', name: 'author_post_list')]
    public function list(PostRepository $postRepository): Response
    {
        // Access only for author or admin
        if (!$this->isGranted('ROLE_AUTHOR') && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        // Admin sees all posts, author only their own
        $posts = $this->isGranted('ROLE_ADMIN')
            ? $postRepository->findAll()
            : $postRepository->findBy(['author' => $this->getUser()]);

        return $this->render('author/post_list.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/post/new', name: 'author_post_new')]
    public function new(Request $request, PostService $postService): Response
    {
        if (!$this->isGranted('ROLE_AUTHOR') && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $post = new Post();
        $form = $this->createForm(PostFormType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postService->createPost($post, $this->getUser());
            $this->addFlash('success', 'Post has been created.');
            return $this->redirectToRoute('author_post_list');
        }

        return $this->render('author/post_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/post/{id}/edit', name: 'author_post_edit')]
    public function edit(Post $post, Request $request, PostService $postService): Response
    {
        if (!$this->isGranted('ROLE_AUTHOR') && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        // Author can edit only their own posts, admin can edit any
        if ($post->getAuthor() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('You cannot edit someone else’s post.');
        }

        $form = $this->createForm(PostFormType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postService->updatePost($post);
            $this->addFlash('success', 'Post has been updated.');
            return $this->redirectToRoute('author_post_list');
        }

        return $this->render('author/post_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/post/{id}/delete', name: 'author_post_delete', methods: ['POST'])]
    public function delete(Post $post, PostService $postService, Request $request): Response
    {
        if (!$this->isGranted('ROLE_AUTHOR') && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        // Author can delete only their own posts, admin can delete any
        if ($post->getAuthor() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('You cannot delete someone else’s post.');
        }

        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $postService->deletePost($post);
            $this->addFlash('success', 'Post has been deleted.');
        } else {
            $this->addFlash('danger', 'Invalid CSRF token.');
        }

        return $this->redirectToRoute('author_post_list');
    }

    #[Route('/post/{id}/toggle-publish', name: 'author_post_toggle_publish', methods: ['POST'])]
    public function togglePublish(Post $post, Request $request, PostService $postService): Response
    {
        if (!$this->isGranted('ROLE_AUTHOR') && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        if ($post->getAuthor() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('You cannot change the publication status of someone else’s post.');
        }

        if ($this->isCsrfTokenValid('toggle'.$post->getId(), $request->request->get('_token'))) {
            $post->setIsPublished(!$post->isPublished());
            $postService->updatePost($post);
            $this->addFlash('success', 'Publication status has been changed.');
        } else {
            $this->addFlash('danger', 'Invalid CSRF token.');
        }

        return $this->redirectToRoute('author_post_list');
    }
}
