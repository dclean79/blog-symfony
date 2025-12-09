<?php
namespace App\Service;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\MarkdownService;

class PostService
{
    public function __construct(
        private EntityManagerInterface $em,
        private MarkdownService $markdownService
    ) {}

    public function createPost(Post $post, User $author): void
    {
        $post->setAuthor($author);
        $post->setCreatedAt(new \DateTimeImmutable());
        $post->setUpdatedAt(new \DateTimeImmutable());
        $post->setIsPublished(false);
        $post->setContentHtml($this->markdownService->toHtml($post->getContentMarkdown()));

        $this->em->persist($post);
        $this->em->flush();
    }

    public function updatePost(Post $post): void
    {
        $post->setUpdatedAt(new \DateTimeImmutable());

        $post->setContentHtml($this->markdownService->toHtml($post->getContentMarkdown()));

        $this->em->flush();
    }

    public function deletePost(Post $post): void
    {
        $this->em->remove($post);
        $this->em->flush();
    }

    public function getPublishedPosts(): array
    {
        return $this->em->getRepository(Post::class)->findPublished();
    }

    public function getLatestPublishedPosts(int $limit = 10): array
    {
        return $this->em->getRepository(Post::class)
            ->createQueryBuilder('p')
            ->andWhere('p.isPublished = :published')
            ->setParameter('published', true)
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

}