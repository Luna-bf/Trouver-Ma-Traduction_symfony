<?php

namespace App\Entity;

use App\Repository\TranslationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TranslationRepository::class)]
class Translation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 500)]
    private ?string $image_file_format_url = null;

    #[ORM\Column(length: 255)]
    private ?string $file_viewer_url = null;

    #[ORM\Column(length: 255)]
    private ?string $translation_type = null;

    #[ORM\Column(length: 255)]
    private ?string $translation_style = null;

    #[ORM\Column(length: 255)]
    private ?string $author = null;

    #[ORM\Column(length: 255)]
    private ?string $language = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'translations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Profile $profile = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getImageFileFormatUrl(): ?string
    {
        return $this->image_file_format_url;
    }

    public function setImageFileFormatUrl(string $image_file_format_url): static
    {
        $this->image_file_format_url = $image_file_format_url;

        return $this;
    }

    public function getFileViewerUrl(): ?string
    {
        return $this->file_viewer_url;
    }

    public function setFileViewerUrl(string $file_viewer_url): static
    {
        $this->file_viewer_url = $file_viewer_url;

        return $this;
    }

    public function getTranslationType(): ?string
    {
        return $this->translation_type;
    }

    public function setTranslationType(string $translation_type): static
    {
        $this->translation_type = $translation_type;

        return $this;
    }

    public function getTranslationStyle(): ?string
    {
        return $this->translation_style;
    }

    public function setTranslationStyle(string $translation_style): static
    {
        $this->translation_style = $translation_style;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): static
    {
        $this->language = $language;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(?Profile $profile): static
    {
        $this->profile = $profile;

        return $this;
    }
}
