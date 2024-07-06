<?php

namespace App\Helper\Writer;

use App\Helper\Reader\FileContent;
use DOMDocument;
use SimpleXMLElement;
use Symfony\Component\String\Inflector\EnglishInflector;

final class XMLWriter extends AbstractWriter {

    public function write(FileContent $content)
    : void {

        $contentToWrite = $this->getContentAsArray($content);

        $inflector = new EnglishInflector();
        $childXML = $inflector->singularize($this->fileName)[0];
        $xml = new SimpleXMLElement("<$this->fileName/>");

        foreach ($contentToWrite as $item) {
            $child = $xml->addChild($childXML);
            foreach ($item as $key => $value) {
                $child->addChild($key, $value);
            }
        }

        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());

        file_put_contents("$this->saveLocation/$this->fileName.xml", $dom->saveXML());
    }

    private function getContentAsArray(FileContent $content)
    : array {

        $output = [];
        foreach ($content->data as $datum) {
            $result = [];
            foreach ($datum as $key => $value) {
                $result[$content->keys[$key]] = $value;
            }
            $output[] = $result;
        }

        return $output;
    }
}

