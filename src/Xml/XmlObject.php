<?php

namespace Mirolabs\Sax\Xml;

use mirolabs\collection\ArrayList;

class XmlObject {
    
    /**
     *
     * @var XmlObject
     */
    private $parent;
    
    
    /**
     *
     * @var string
     */
    private $tagName;

    /**
     *
     * @var string
     */
    private $content;
    
    /**
     *
     * @var ArrayList
     */
    private $children;
    
    /**
     *
     * @var ArrayList
     */
    private $attributes;
    
    
    function __construct($tagName, $parent=null) {
        $this->parent = $parent;
        $this->tagName = $tagName;
        $this->attributes = new ArrayList('\\Mirolabs\\Sax\\Xml\\XmlAttribute');
        $this->children = new ArrayList('\\Mirolabs\\Sax\\Xml\\XmlObject');
    }

    
    public function getTagName() {
        return $this->tagName;
    }

    public function getChildren(): ArrayList {
        return $this->children;
    }

    public function getAttributes(): ArrayList {
        return $this->attributes;
    }

    public function addChild(XmlObject $child) {
        $this->children->add($child);
    }

    public function addAttribute(XmlAttribute $attribute) {
        $this->attributes->add($attribute);
    }
    
    public function getContent() {
        return $this->content;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function hasChildren() : bool {
        return $this->children->size() > 0;
    }
    
    /**
     * 
     * @return Xml
     */
    public function getParent() {
        return $this->parent;
    }

    public function getAttributeValue($name) {
        $obj = $this->attributes->find(function (XmlAttribute $attribute) use ($name) {
            return $attribute->getName() == $name;
        });
        return $obj ? $obj->getValue() : null;
    }


    /**
     * 
     * @param type $name
     * @param type $recursive
     * @return XmlObject
     */
    public function findTag($name, $recursive = false) {
        return $this->findAllTag($name, $recursive)->current();
    }
    
    public function findAllTag($name, $recursive = false) : ArrayList {
        $result = $this->children->filter(function (XmlObject $obj) use ($name, $recursive) {
            return $obj->getTagName() == $name;
        });
        if ($recursive) {
            if ($result->size() ==0) {
                $result = new ArrayList('\\Mirolabs\\Sax\\Xml\\XmlObject');
            }
            
            foreach ($this->children as $child) {
                foreach($child->findAllTag($name, true) as $tag) {
                    $result->add($tag);
                }
            }
        }
        return $result;
    }


}
