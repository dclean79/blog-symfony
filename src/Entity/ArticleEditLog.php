<?php

namespace App\Entity;

use App\Repository\ArticleEditLogRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleEditLogRepository::class)]
class ArticleEditLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Post $post = null;

    #[ORM\ManyToOne(inversedBy: 'articleEditLogs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $editor = null;

    #[ORM\Column]
    private \DateTimeImmutable $editedAt;

    #[ORM\Column(type: Types::TEXT)]
    private string $changeSummary;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $oldTitle = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $oldContentMarkdown = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $newTitle = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $newContentMarkdown = null;

    public function __construct()
    {
        $this->editedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): static
    {
        $this->post = $post;
        return $this;
    }

    public function getEditor(): ?User
    {
        return $this->editor;
    }

    public function setEditor(?User $editor): static
    {
        $this->editor = $editor;
        return $this;
    }

    public function getEditedAt(): \DateTimeImmutable
    {
        return $this->editedAt;
    }

    public function setEditedAt(\DateTimeImmutable $editedAt): static
    {
        $this->editedAt = $editedAt;
        return $this;
    }

    public function getChangeSummary(): string
    {
        return $this->changeSummary;
    }

    public function setChangeSummary(string $changeSummary): static
    {
        $this->changeSummary = $changeSummary;
        return $this;
    }

    public function getOldTitle(): ?string
    {
        return $this->oldTitle;
    }

    public function setOldTitle(?string $oldTitle): static
    {
        $this->oldTitle = $oldTitle;
        return $this;
    }

    public function getOldContentMarkdown(): ?string
    {
        return $this->oldContentMarkdown;
    }

    public function setOldContentMarkdown(?string $oldContentMarkdown): static
    {
        $this->oldContentMarkdown = $oldContentMarkdown;
        return $this;
    }

    public function getNewTitle(): ?string
    {
        return $this->newTitle;
    }

    public function setNewTitle(?string $newTitle): static
    {
        $this->newTitle = $newTitle;
        return $this;
    }

    public function getNewContentMarkdown(): ?string
    {
        return $this->newContentMarkdown;
    }

    public function setNewContentMarkdown(?string $newContentMarkdown): static
    {
        $this->newContentMarkdown = $newContentMarkdown;
        return $this;
    }
}
