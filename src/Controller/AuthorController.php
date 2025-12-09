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
        // Dostęp tylko dla autora lub admina
        if (!$this->isGranted('ROLE_AUTHOR') && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        // Admin widzi wszystkie posty, autor tylko swoje
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
            $this->addFlash('success', 'Post został dodany.');
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

        // Autor może edytować tylko swoje posty, admin dowolne
        if ($post->getAuthor() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Nie możesz edytować cudzych postów.');
        }

        $form = $this->createForm(PostFormType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postService->updatePost($post);
            $this->addFlash('success', 'Post został zaktualizowany.');
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

        // Autor może usuwać tylko swoje posty, admin dowolne
        if ($post->getAuthor() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Nie możesz usuwać cudzych postów.');
        }

        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $postService->deletePost($post);
            $this->addFlash('success', 'Post został usunięty.');
        } else {
            $this->addFlash('danger', 'Nieprawidłowy token bezpieczeństwa.');
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
            throw $this->createAccessDeniedException('Nie możesz zmieniać statusu cudzych postów.');
        }

        if ($this->isCsrfTokenValid('toggle'.$post->getId(), $request->request->get('_token'))) {
            $post->setIsPublished(!$post->isPublished());
            $postService->updatePost($post);
            $this->addFlash('success', 'Status publikacji został zmieniony.');
        } else {
            $this->addFlash('danger', 'Nieprawidłowy token bezpieczeństwa.');
        }

        return $this->redirectToRoute('author_post_list');
    }

}
