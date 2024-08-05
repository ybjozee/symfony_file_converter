<?= "<?php\n" ?>
namespace <?= $namespace ?>;

use App\Helper\Reader\{AbstractReader, <?= $readerClassName ?>};
use App\Helper\Writer\{AbstractWriter, <?= $writerClassName ?>};

class <?= $class_name; ?> implements FileConverterInterface {

    public function supports(string $fileExtension) : bool {
        //TODO: Condition for which converter can be used
    }

    public function getReader(): AbstractReader {
        //TODO: Return instance of reader class
    }

    public function getWriter(string $saveLocation, string $fileName): AbstractWriter {
        //TODO: Return instance of writer class
    }

    public function getSupportedFormat(): string {
        //TODO: Return string representation of supported format
    }
}

