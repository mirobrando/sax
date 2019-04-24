<?php

namespace Mirolabs\Sax;

class Finder {
    
    private $callback;
    private $buffor;
    private $parser;
    private $xmlTag;

    /**
     *
     * @var State\XmlState
     */
    private $state;


    /**
     * 
     * @param \Closure $callback
     * @param int $buffor
     */
    public function __construct(\Closure $callback, $buffor=1024) {
        $this->callback = $callback;
        $this->buffor = $buffor;
        $this->state = new State\Close();
    }
    
    
    /**
     * 
     * @param string $filePath
     */
    public function find($filePath, $xmlTag) {
        $this->xmlTag = $xmlTag;
        $stream = fopen($filePath, 'r');
        $this->parser = xml_parser_create();
        xml_set_object($this->parser, $this);
        xml_set_element_handler($this->parser, "tag_open", "tag_close");
        xml_set_character_data_handler($this->parser, "cdata");
        xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, false); 
        
        
        while (($data = fread($stream, $this->buffor))) {
           xml_parse($this->parser, $data); // parse the current chunk
        }
        
        xml_parse($this->parser, '', true); // finalize parsing
        xml_parser_free($this->parser);
        fclose($stream);
    }
    
    public function tag_open($parser, $tag, $attributes) {
        if ($this->state->getXmlObject() == null) {
            if ($tag == $this->xmlTag) {
                $this->state = $this->state->openTag($tag, $attributes);
            }
        } else {
            $this->state = $this->state->openTag($tag, $attributes);
        }
    }

    public function cdata($parser, $cdata) {
        $this->state = $this->state->setContent($cdata);
    }

    public function tag_close($parser, $tag) {
        $current = $this->state->getXmlObject();
        if ($current != null) {
            $this->state = $this->state->closeTag();
            if ($this->state->getXmlObject() == null) {
                $callback = $this->callback;
                $callback($current);
            }
        }
    }
    
}