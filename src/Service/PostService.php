<?php
namespace App\Service;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class PostService
{
    public function __construct(private EntityManagerInterface $em) {}

    public function createPost(Post $post, User $author): void
    {
        $post->setAuthor($author);
        $post->setCreatedAt(new \DateTimeImmutable());
        $post->setUpdatedAt(new \DateTimeImmutable());
        $post->setIsPublished(false);

        $this->em->persist($post);
        $this->em->flush();
    }

    public function updatePost(Post $post): void
    {
        $post->setUpdatedAt(new \DateTimeImmutable());
        $this->em->flush();
    }
}