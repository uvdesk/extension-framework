<?php

namespace Webkul\UVDesk\ExtensionFrameworkBundle\Definition\Application;

abstract class ApplicationMetadata
{
    public function getIconPath() : string
    {
        return '';
    }

    public abstract function getName() : string;

    public abstract function getSummary() : string;

    public abstract function getDescription() : string;

    public abstract function getQualifiedName() : string;
}
