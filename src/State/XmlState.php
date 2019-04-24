<?php

namespace Mirolabs\Sax\State;

interface XmlState {
    function getXmlObject();
    function openTag($tag, $attributes)  : XmlState;
    function closeTag() : XmlState;
    function setContent($content)  : XmlState;
}
