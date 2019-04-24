<?php
#stream api xml

## use example

    $file = 'file.xml';

    $callback = function(\Mirolabs\Sax\Xml\XmlObject $obj) {
        var_dump($obj->findTag('img', true)->getContent());
    };

    $finder = new Mirolabs\Sax\Finder($callback, 128);
    $finder->find($file, 'event');

