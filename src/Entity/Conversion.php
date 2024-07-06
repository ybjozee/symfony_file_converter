<?php

namespace App\Entity;

use App\Repository\ConversionRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConversionRepository::class)]
class Conversion {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column]
    private string $uploadedFileName;
    #[ORM\Column]
    private string $uploadedFileSize;
    #[ORM\Column]
    private string $uploadedFileFormat;
    #[ORM\Column]
    private string $convertedFileName;
    #[ORM\Column]
    private string $convertedFileSize;
    #[ORM\Column]
    private string $convertedFileFormat;
    #[ORM\Column]
    private string $nameToSaveAs;
    #[ORM\Column]
    private DateTimeImmutable $conversionDate;

    public function __construct() {

        $this->conversionDate = new DateTimeImmutable;
    }

    public function getId()
    : ?int {

        return $this->id;
    }

    public function getUploadedFileName()
    : string {

        return $this->uploadedFileName;
    }

    public function setUploadedFileName(string $uploadedFileName)
    : void {

        $this->uploadedFileName = $uploadedFileName;
    }

    public function getUploadedFileSize()
    : string {

        return $this->uploadedFileSize;
    }

    public function setUploadedFileSize(int $uploadedFileSize)
    : void {

        $this->uploadedFileSize = $this->readableFileSize($uploadedFileSize);
    }

    private function readableFileSize(int $size)
    : string {

        $i = floor(log($size) / log(1024));

        $sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

        return sprintf('%.02F', $size / pow(1024, $i)).' '.$sizes[$i];
    }

    public function getUploadedFileFormat()
    : string {

        return $this->uploadedFileFormat;
    }

    public function setUploadedFileFormat(string $uploadedFileFormat)
    : void {

        $this->uploadedFileFormat = $uploadedFileFormat;
    }

    public function getConvertedFileName()
    : string {

        return "$this->convertedFileName.$this->convertedFileFormat";
    }

    public function setConvertedFileName(string $convertedFileName)
    : void {

        $this->convertedFileName = $convertedFileName;
    }

    public function getConvertedFileSize()
    : string {

        return $this->convertedFileSize;
    }

    public function setConvertedFileSize(int $convertedFileSize)
    : void {

        $this->convertedFileSize = $this->readableFileSize($convertedFileSize);
    }

    public function getConvertedFileFormat()
    : string {

        return $this->convertedFileFormat;
    }

    public function setConvertedFileFormat(string $convertedFileFormat)
    : void {

        $this->convertedFileFormat = $convertedFileFormat;
    }

    public function getNameToSaveAs()
    : string {

        return $this->nameToSaveAs;
    }

    public function setNameToSaveAs(string $nameToSaveAs)
    : void {

        $this->nameToSaveAs = $nameToSaveAs;
    }

    public function getConversionDate()
    : DateTimeImmutable {

        return $this->conversionDate;
    }
}

