<?php
//issue formbuilder controller 
namespace Webkul\UVDesk\ExtensionFrameworkBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FormBuilderChannelXHR extends Controller 
{
    const CONFIG_FILE = __DIR__."/../../../../config/uvdesk_formbuilder.json"; 

    public function loadFormsXHR(Request $request)
    {
        return new JsonResponse(json_decode(file_get_contents(self::CONFIG_FILE), true));
    }

    public function removeFormConfiguration($id, Request $request)
    {
        $array = json_decode( file_get_contents(self::CONFIG_FILE), true);
        foreach($array as $key=>$value)
        {
            if($value['id'] == $id)
            {
                unset($array[$key]);
            }
        }

        file_put_contents(self::CONFIG_FILE, '');
        file_put_contents(self::CONFIG_FILE, json_encode($array), FILE_APPEND);

        return new JsonResponse([
            'alertClass' => 'success',
            'alertMessage' => 'Form configuration removed successfully.',
        ]);
       
    }
  
}
?>