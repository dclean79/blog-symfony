<?php

namespace App\Entity;

use App\Repository\BlogSettingsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BlogSettingsRepository::class)]
class BlogSettings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $blogTitle = 'My Blog';

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $blogSubtitle = null;

    #[ORM\Column(length: 255)]
    private string $defaultAuthorRole = 'ROLE_AUTHOR';

    #[ORM\Column(type: 'boolean')]
    private bool $allowRegistration = true;

    #[ORM\Column(type: 'boolean')]
    private bool $requireEmailActivation = true;

    #[ORM\Column(type: 'boolean')]
    private bool $allowComments = true;

    #[ORM\Column(type: 'boolean')]
    private bool $commentsRequireApproval = true;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $markdownExtensions = null;

    #[ORM\Column(length: 255)]
    private string $theme = 'default';

    #[ORM\Column]
    private int $itemsPerPage = 10;

    #[ORM\Column(type: 'boolean')]
    private bool $showEditLogFootnote = false;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column]
    private \DateTimeImmutable $updatedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBlogTitle(): string
    {
        return $this->blogTitle;
    }

    public function setBlogTitle(string $blogTitle): static
    {
        $this->blogTitle = $blogTitle;
        return $this;
    }

    public function getBlogSubtitle(): ?string
    {
        return $this->blogSubtitle;
    }

    public function setBlogSubtitle(?string $blogSubtitle): static
    {
        $this->blogSubtitle = $blogSubtitle;
        return $this;
    }

    public function getDefaultAuthorRole(): string
    {
        return $this->defaultAuthorRole;
    }

    public function setDefaultAuthorRole(string $defaultAuthorRole): static
    {
        $this->defaultAuthorRole = $defaultAuthorRole;
        return $this;
    }

    public function isAllowRegistration(): bool
    {
        return $this->allowRegistration;
    }

    public function setAllowRegistration(bool $allowRegistration): static
    {
        $this->allowRegistration = $allowRegistration;
        return $this;
    }

    public function isRequireEmailActivation(): bool
    {
        return $this->requireEmailActivation;
    }

    public function setRequireEmailActivation(bool $requireEmailActivation): static
    {
        $this->requireEmailActivation = $requireEmailActivation;
        return $this;
    }

    public function isAllowComments(): bool
    {
        return $this->allowComments;
    }

    public function setAllowComments(bool $allowComments): static
    {
        $this->allowComments = $allowComments;
        return $this;
    }

    public function isCommentsRequireApproval(): bool
    {
        return $this->commentsRequireApproval;
    }

    public function setCommentsRequireApproval(bool $commentsRequireApproval): static
    {
        $this->commentsRequireApproval = $commentsRequireApproval;
        return $this;
    }

    public function getMarkdownExtensions(): ?array
    {
        return $this->markdownExtensions;
    }

    public function setMarkdownExtensions(?array $markdownExtensions): static
    {
        $this->markdownExtensions = $markdownExtensions;
        return $this;
    }

    public function getTheme(): string
    {
        return $this->theme;
    }

    public function setTheme(string $theme): static
    {
        $this->theme = $theme;
        return $this;
    }

    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }

    public function setItemsPerPage(int $itemsPerPage): static
    {
        $this->itemsPerPage = $itemsPerPage;
        return $this;
    }

    public function isShowEditLogFootnote(): bool
    {
        return $this->showEditLogFootnote;
    }

    public function setShowEditLogFootnote(bool $showEditLogFootnote): static
    {
        $this->showEditLogFootnote = $showEditLogFootnote;
        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}
