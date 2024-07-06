<?php

namespace App\Helper\Maker;

use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

/**
 * @method string getCommandDescription()
 */
class MakeConverter extends AbstractMaker {

    /**
     * @inheritDoc
     */
    public static function getCommandName()
    : string {

        return 'make:converter';
    }

    /**
     * @inheritDoc
     */
    public function configureCommand(Command $command, InputConfiguration $inputConfig)
    : void {

        $command->addArgument(
            'data-format',
            InputArgument::OPTIONAL,
            'The type of converter you want to create (e.g. <fg=yellow>XML</>)',
        )->setHelp(file_get_contents(__DIR__.'/Resources/help/make_converter.txt'));
    }

    public static function getCommandDescription()
    : string {

        return 'Create the required classes for a new converter';
    }

    /**
     * @inheritDoc
     */
    public function configureDependencies(DependencyBuilder $dependencies)
    : void {
    }

    /**
     * @inheritDoc
     */
    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    : void {

        $dataFormat = $input->getArgument('data-format');

        //create reader
        $readerClassNameDetails = $generator->createClassNameDetails("Reader\\$dataFormat", 'Helper', 'Reader');
        $generator->generateClass(
            $readerClassNameDetails->getFullName(),
            __DIR__.'/Resources/skeleton/Reader.tpl.php',
        );

        //create writer
        $writerClassNameDetails = $generator->createClassNameDetails("Writer\\$dataFormat", 'Helper', 'Writer');
        $generator->generateClass(
            $writerClassNameDetails->getFullName(),
            __DIR__.'/Resources/skeleton/Writer.tpl.php'
        );

        //create converter
        $converterClassNameDetails =
            $generator->createClassNameDetails("Converter\\$dataFormat", 'Helper', 'Converter');
        $generator->generateClass(
            $converterClassNameDetails->getFullName(),
            __DIR__.'/Resources/skeleton/Converter.tpl.php',
            [
                'readerClassName' => $readerClassNameDetails->getShortName(),
                'writerClassName' => $writerClassNameDetails->getShortName(),
            ]
        );

        $generator->writeChanges();

        $io->newLine();
        $io->writeln('<fg=green>Your reader, writer, and converter have been created successfully</>.');
        $io->writeln(
            'Please <fg=yellow>review</>, <fg=yellow>edit</> and <fg=yellow>commit</> them: these files are <fg=yellow>yours</>.'
        );
        $io->newLine();
    }
}

