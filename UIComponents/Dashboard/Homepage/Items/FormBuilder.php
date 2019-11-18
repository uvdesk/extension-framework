<?php

namespace Webkul\UVDesk\ExtensionFrameworkBundle\UIComponents\Dashboard\Homepage\Items;

use Webkul\UVDesk\CoreFrameworkBundle\Dashboard\Segments\HomepageSectionItem;
use Webkul\UVDesk\ExtensionFrameworkBundle\UIComponents\Dashboard\Homepage\Sections\Apps;

class FormBuilder extends HomepageSectionItem
{
    CONST SVG = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="60px" height="60px" viewBox="0 0 60 60">
    <path fill-rule="evenodd" d="M51,57H9a6,6,0,0,1-6-6V9A6,6,0,0,1,9,3H51a6,6,0,0,1,6,6V51A6,6,0,0,1,51,57ZM11,49H33V41H11v8ZM49,11H11V21H49V11Zm0,15H11V36H49V26Zm-3,7H14V29H46v4Zm0-15H14V14H46v4Z"></path>
</svg>
SVG;


    public static function getIcon() : string
    {
        return self::SVG;
    }

    public static function getTitle() : string
    {
        return "Form Builder";
    }

    public static function getRouteName() : string
    {
        // return 'uvdesk_extensions_applications_dashboard';
        return 'formbuilder_settings'; 
    }

    public static function getSectionReferenceId() : string
    {
        return Apps::class;
    }
}