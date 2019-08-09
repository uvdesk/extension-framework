<?php

namespace Webkul\UVDesk\ExtensionFrameworkBundle\Definition\Application;

abstract class ApplicationMetadata
{
    CONST SVG = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="60px" height="60px" viewBox="0 0 60 60">
    <path fill-rule="evenodd" d="M17,26a4,4,0,1,1-4,4A4,4,0,0,1,17,26Zm13,0a4,4,0,1,1-4,4A4,4,0,0,1,30,26Zm13,0a4,4,0,1,1-4,4A4,4,0,0,1,43,26Z"></path>
</svg>
SVG;

    public function getIcon() : string
    {
        return self::SVG;
    }

    public abstract function getName() : string;

    public abstract function getSummary() : string;

    public abstract function getDescription() : string;

    public abstract function getQualifiedName() : string;
}
