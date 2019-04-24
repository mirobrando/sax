<?php
namespace Mirolabs\Sax\State;

use Mirolabs\Sax\Xml\XmlObject;
use Mirolabs\Sax\Xml\XmlAttribute;

class Close implements XmlState {
    
    /**
     *
     * @var \Mirolabs\Sax\Xml\XmlObject
     */
    private $current;
    
    /**
     *
     * @var Open
     */
    private $openState;


    public function __construct() {
        $this->openState = new Open($this);
    }
    
    public function setCurrent($current) {
        $this->current = $current;
    }
    
    /**
     * 
     * @return XmlObject
     */
    public function getXmlObject() {
        return $this->current;
    }

    public function closeTag() : XmlState {
        if ($this->current != null) {
            $this->current = $this->current->getParent();
        }
        return $this;
    }

    public function openTag($tag, $attributes)  : XmlState {
        $xml = new XmlObject($tag, $this->current);
        if ($this->current != null) {
            $this->current->addChild($xml);
        }
        foreach($attributes as $key=>$value) {
            $xml->addAttribute(new XmlAttribute($key, $value));
        }
        $this->openState->setCurrent($xml);
        return $this->openState;
    }

    public function setContent($content)  : XmlState {
        return $this;
    }

}
