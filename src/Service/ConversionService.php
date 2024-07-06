<?php

namespace App\Service;

use App\Entity\Conversion;
use App\Helper\Converter\FileConverterInterface;
use App\Helper\Converter\UnsupportedFormatException;
use App\Helper\Reader\FileReadException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ConversionService {

    /**@var FileConverterInterface[] $converters */
    private array $converters;

    private array $availableFormats;

    public function __construct(
        #[AutowireIterator('app.converter')]
        iterable                       $converters,
        #[Autowire('%env(resolve:OUTPUT_FOLDER)%')]
        private readonly string        $saveLocation,
        private EntityManagerInterface $em
    ) {

        $this->converters = iterator_to_array($converters);
        $this->setAvailableFormats();
    }

    private function setAvailableFormats()
    : void {

        $callback = function (array $carry, FileConverterInterface $converter)
        : array {

            $supportedFormat = $converter->getSupportedFormat();

            return $carry + [strtoupper($supportedFormat) => $supportedFormat];
        };

        $this->availableFormats = array_reduce($this->converters, $callback, []);
    }

    public function getAvailableFormats()
    : array {

        return $this->availableFormats;
    }

    /**
     * @throws UnsupportedFormatException| FileReadException
     */
    public function convert(Conversion $conversion, UploadedFile $uploadedFile)
    : void {

        $conversion->setUploadedFileSize($uploadedFile->getSize());
        $conversion->setUploadedFileName($uploadedFile->getClientOriginalName());
        $inputFormat = $uploadedFile->getClientOriginalExtension();
        $conversion->setUploadedFileFormat($inputFormat);
        $outputFormat = $conversion->getConvertedFileFormat();

        if ($inputFormat === $outputFormat) {
            throw new FileReadException('Both formats cannot be the same');
        }

        $fileName = uniqid("FCA_");
        $reader = $this->getAppropriateConverter($inputFormat)->getReader();
        $reader->read($uploadedFile->getRealPath());
        $parsedContent = $reader->getContent();

        $writer = $this->getAppropriateConverter($outputFormat)->getWriter($this->saveLocation, $fileName);
        $writer->write($parsedContent);

        $conversion->setConvertedFileName($fileName);
        $conversion->setConvertedFileSize(filesize("$this->saveLocation/{$conversion->getConvertedFileName()}"));

        $this->em->persist($conversion);
        $this->em->flush();
    }

    /**
     * @throws UnsupportedFormatException
     */
    private function getAppropriateConverter(string $format)
    : FileConverterInterface {

        foreach ($this->converters as $converter) {
            if ($converter->supports($format)) {
                return $converter;
            }
        }
        throw new UnsupportedFormatException($format);
    }
}

