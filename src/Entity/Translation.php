<?php

namespace App\Entity;

use App\Repository\TranslationRepository;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Events;

enum Type: string {
    case Chanson = 'Chanson';
    case Livre = 'Livre';
    case Texte = 'Texte';
}

enum Style: string {
    case Swing = 'Swing (Chanson)';
    case Pop = 'Pop (Chanson)';
    case Rock = 'Rock (Chanson)';
    case Fantasy = 'Fantasy (Livre)';
    case Policier = 'Policier (Livre)';
    case Romance = 'Romance (Livre)';
    case Poème = 'Poème (Texte)';
    case Fable = 'Fable (Texte)';
    case Légende = 'Légende (Texte)';
    case Autre = 'Autre';
}

#[ORM\Entity(repositoryClass: TranslationRepository::class)]
// #[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: Translation::class)]
class Translation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 500)]
    private ?string $translationFileName = null;

    #[ORM\Column(type: 'string', enumType: type::class)]
    private ?Type $translationType = null;

    #[ORM\Column(type: 'string', enumType: style::class)]
    private ?Style $translationStyle = null;

    #[ORM\Column(length: 255)]
    private ?string $author = null;

    #[ORM\Column(length: 255)]
    private ?string $language = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'translations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Profile $profile = null;

    #[ORM\Column(length: 20)]
    private ?string $translationFileExtension = null;

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

    public function getTranslationFileName(): ?string
    {
        return $this->translationFileName;
    }

    public function setTranslationFileName(string $translationFileName): static
    {
        $this->translationFileName = $translationFileName;

        return $this;
    }

    // Le getter retourne maintenant l'objet enum et non une chaîne de caractère vu que le type de cette donnée est Enum
    public function getTranslationType(): ?Type
    {
        return $this->translationType;
    }

    public function setTranslationType(Type $translationType): static
    {
        $this->translationType = $translationType;

        return $this;
    }

    // Le getter retourne maintenant l'objet enum et non une chaîne de caractère vu que le type de cette donnée est Enum
    public function getTranslationStyle(): ?Style
    {
        return $this->translationStyle;
    }

    public function setTranslationStyle(Style $translationStyle): static
    {
        $this->translationStyle = $translationStyle;

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

    /**
    * Called before saving the entity
    * 
    * @ORM\PrePersist()
    * @ORM\PreUpdate()
    */
    // public function preUpload()
    // {
    //     $oldFile = $this->translationFileName;
    //     $oldFilePath = $this->getUploadRootDir().'/'.$oldFile;

    //     if (null !== $this->translationFileName) {
    //         if($oldFile && file_exists($oldFilePath)) unlink($oldFilePath); // not working correctly
    //         $translationFileName = sha1(uniqid(mt_rand(), true));
    //         $this->translationFileName = $translationFileName . '.' . $this->translationFile->guessExtension();
    //     }
    // }

    /**
    * Called before entity removal
    *
    * @ORM\PostRemove()
    */
    // public function removeUpload()
    // {
    //     if ($file = $this->getAbsolutePath()) {
    //         unlink($file);
    //     }
    // }

    /*
    Source utile :
        https://stackoverflow.com/questions/19563295/symfony2-file-upload-delete-old-and-create-new-in-edit
    */
    // public function postUpdate(Translation $translation, PostUpdateEventArgs $event): void
    // {
    //     // ... do something to notify the changes

    // }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(?Profile $profile): static
    {
        $this->profile = $profile;

        return $this;
    }

    public function getTranslationFileExtension(): ?string
    {
        return $this->translationFileExtension;
    }

    public function setTranslationFileExtension(string $translationFileExtension): static
    {
        $this->translationFileExtension = $translationFileExtension;

        return $this;
    }
}
