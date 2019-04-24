<?php

namespace Mirolabs\Sax\State;

use Mirolabs\Sax\Xml\XmlObject;
use Mirolabs\Sax\Xml\XmlAttribute;

class Open implements XmlState {
    
    /**
     *
     * @var \Mirolabs\Sax\Xml\XmlObject
     */
    private $current;
    
    /**
     *
     * @var Close
     */
    private $closeState;
    
    function __construct(Close $closeState) {
        $this->closeState = $closeState;
    }

    public function setCurrent(\Mirolabs\Sax\Xml\XmlObject $current) {
        $this->current = $current;
    }

    public function closeTag(): \Mirolabs\Sax\State\XmlState {
        $this->closeState->setCurrent($this->current->getParent());
        return $this->closeState;
    }

    public function getXmlObject() {
        return $this->current;
    }

    public function openTag($tag, $attributes): XmlState {
        $xml = new XmlObject($tag, $this->current);
        $this->current->addChild($xml);
        foreach($attributes as $key=>$value) {
            $xml->addAttribute(new XmlAttribute($key, $value));
        }
        $this->setCurrent($xml);
        return $this;
    }

    public function setContent($content): XmlState {
        $this->current->setContent($content);
        return $this;
    }

}
