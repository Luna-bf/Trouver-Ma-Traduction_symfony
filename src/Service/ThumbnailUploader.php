<?php

// src/Service/ThumbnailUploader.php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Mime\Part\File;
use Symfony\Component\String\Slugger\SluggerInterface;

class ThumbnailUploader {
    public function __construct(
        private string $thumbnailsDirectory,
        private SluggerInterface $slugger,
    ) {
    }

    public function upload(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $thumbnailName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $thumbnailName);
        } catch (FileException $e) {
            throw new FileException("Une erreur est survenue.");
        }

        return $thumbnailName;
    }

    public function getTargetDirectory(): string
    {
        return $this->thumbnailsDirectory;
    }
}