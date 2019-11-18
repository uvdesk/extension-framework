<?php 

namespace Webkul\UVDesk\ExtensionFrameworkBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Webkul\UVDesk\CoreFrameworkBundle\Entity\SupportRole;
use Webkul\UVDesk\CoreFrameworkBundle\Services\UserService;
use UVDesk\CommunityPackages\UVDesk\FormComponent\Entity\Ticket; 
use Webkul\UVDesk\CoreFrameworkBundle\Entity\TicketType; 

class FormBuilderChannel extends Controller
{
    const CONFIG_FILE = __DIR__."/../../../../config/uvdesk_formbuilder.json"; 

    public function loadFormBuilders()
    {
        return $this->render('@UVDeskExtensionFramework//listConfigurations.html.twig', []);
    }
    
    public function createFormBuilderConfiguration(Request $request)
    {

        if($request->getMethod() == 'POST')
        {
            $params = $request->request->all();
            $dataArray = ['id' => bin2hex(random_bytes(20)),
                'form_name' => $params['form_name'],
                'name'      => (isset($params['name'])     ? '1' : '0' ),
                'type'      => (isset($params['type'])     ? '1' : '0' ),
                'subject'   => (isset($params['subject'])  ? '1' : '0' ),
                'gdpr'      => (isset($params['gdpr'])     ? '1' : '0' ),
                'order_no'  => (isset($params['order_no']) ? '1' : '0' ),
                'file'      => (isset($params['file'])     ? '1' : '0' )];

            $jsonData = json_decode(file_get_contents(self::CONFIG_FILE),true);
            array_push($jsonData,$dataArray); 

            file_put_contents(self::CONFIG_FILE, '');
            file_put_contents(self::CONFIG_FILE, json_encode($jsonData), FILE_APPEND);
            
            $this->addFlash('success', 'Form successfully created.');
            return new RedirectResponse($this->generateUrl('formbuilder_settings'));

        }

        return $this->render('@UVDeskExtensionFramework//manageConfigurations.html.twig', [
                'formbuilder' => [],
        ]);
    }

    public function updateFormConfiguration($id, Request $request)
    {

        $config = null;
        $configs = json_decode(file_get_contents(self::CONFIG_FILE), true);

        foreach($configs as $key=>$value)
        {
            if($value['id'] == $id)
            {
                $config = $configs[$key]; 
            }
        }

        if($request->getMethod() == 'POST')
        {
            $params = $request->request->all();
            $configs = json_decode(file_get_contents(self::CONFIG_FILE), true);

            foreach($configs as $key=>$value)
            {
                if($value['id'] == $id)
                {

                    $configs[$key]['form_name'] = ( empty($params['form_name']) ? $configs[$key]['form_name'] : $params['form_name'] );
                    $configs[$key]['name']      = (isset($params['name'])     ? '1' : '0' );          
                    $configs[$key]['type']      = (isset($params['type'])     ? '1' : '0' );
                    $configs[$key]['subject']   = (isset($params['subject'])  ? '1' : '0' );
                    $configs[$key]['gdpr']      = (isset($params['gdpr'])     ? '1' : '0' );
                    $configs[$key]['order_no']  = (isset($params['order_no']) ? '1' : '0' );
                    $configs[$key]['file']      = (isset($params['file'])     ? '1' : '0' );
                }
            }
            
            file_put_contents(self::CONFIG_FILE, '');
            file_put_contents(self::CONFIG_FILE, json_encode($configs), FILE_APPEND);

            $this->addFlash('success', 'Form successfully updated.');
            return new RedirectResponse($this->generateUrl('formbuilder_settings'));
        }

        return $this->render('@UVDeskExtensionFramework//manageConfigurations.html.twig',[
            'formbuilder' => $config ?? null,
        ]);
    }

    public function previewForm($id, Request $request)
    {
        $array = json_decode(file_get_contents(self::CONFIG_FILE), true);
        $previewForm = null; 
        foreach($array as $key=>$value)
        {
            if($value['id'] == $id)
            {
                $previewForm = $array[$key];
            }
        }

        if( $request->getMethod() == "POST" )
        {
            $params = $request->request->all();
            $role = $this->getDoctrine()->getRepository(SupportRole::class)->find(4);
            $userInstance = $this->get('user.service')->createUserInstance($params['email'], $params['name'], $role);

            $params['from'] = $params['email'];
            $params['subject'] = ( isset($params['subject']) ? $params['subject'] : 'null'); 
            $params['reply'] = $params['reply'];
            $params['fullname'] = $params['name'];
            $params['createdBy'] = 'customer';
            $params['threadType'] = 'create';
            $params['source'] = 'formbuilder';
            $params['role'] = 4;
            $params['customer'] = $userInstance;
            $params['user'] = $userInstance; 
            $params['type'] = $this->getDoctrine()->getRepository(TicketType::class)->find(1); 
            $params['message'] = $params['reply']; 
            $params['gdpr'] = ( isset($params['gdpr']) ? $params['gdpr'] : 'null');
            $params['order_no'] = ( isset($params['order_no']) ? $params['order_no'] : 'null');
            $params['file']  = ( isset($params['file']) ? $params['file'] : 'null');

            $this->get('ticket.service')->createTicketBase($params);

            $this->addFlash('success', 'Ticket Created Successfully!');
            return new RedirectResponse($this->generateUrl('helpdesk_member_ticket_collection'));
        }

        return $this->render('@UVDeskExtensionFramework//previewForm.html.twig', [
            'formbuilder' => $previewForm,
        ]);

    }


}
?>

