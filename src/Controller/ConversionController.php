<?php

namespace App\Controller;

use App\Entity\Conversion;
use App\Form\ConversionType;
use App\Helper\Converter\UnsupportedFormatException;
use App\Helper\Reader\FileReadException;
use App\Repository\ConversionRepository;
use App\Service\ConversionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/conversion/', name: 'app_conversion_')]
class ConversionController extends AbstractController {

    public function __construct(
        #[Autowire('%env(resolve:OUTPUT_FOLDER)%')]
        private readonly string $saveLocation
    ) {
    }

    #[Route('', name: 'index')]
    public function index(ConversionService $converter, Request $request)
    : Response {

        $input = new Conversion;
        $error = null;

        $form = $this->createForm(ConversionType::class, $input);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $uploadedFile = $form->get('file')->getData();
                assert($uploadedFile instanceof UploadedFile);

                $converter->convert($input, $uploadedFile);

                return $this->redirectToRoute('app_conversion_result', ['id' => $input->getId()]);
            }
            catch (UnsupportedFormatException|FileReadException $e) {
                $error = $e->getMessage();
            }
        }

        return $this->render('conversion/index.html.twig', [
            'controller_name' => 'ConverterController',
            'form'            => $form->createView(),
            'error'           => $error,
        ]);
    }

    #[Route('result/{id}', name: 'result', methods: ['GET'])]
    public function getConversion(Conversion $conversion)
    : BinaryFileResponse {

        return $this->file(
            "$this->saveLocation/{$conversion->getConvertedFileName()}",
            "{$conversion->getNameToSaveAs()}.{$conversion->getConvertedFileFormat()}"
        );
    }

    #[Route('history', name: 'history', methods: ['GET'])]
    public function geRecentConversions(ConversionRepository $repository)
    : Response {

        return $this->render('conversion/history.html.twig', [
            'history' => $repository->getConversions(),
        ]);
    }
}

