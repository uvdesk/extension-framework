<?php

namespace Webkul\UVDesk\ExtensionFrameworkBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Webkul\UVDesk\ExtensionFrameworkBundle\Utils\ApplicationCollection;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Webkul\UVDesk\CoreFrameworkBundle\Services\UserService;
class Dashboard extends AbstractController
{
    public function applications(Request $request, UserService $userService)
    {
        if (! $userService->isAccessAuthorized('ROLE_AGENT_MANAGE_APP')) {
            return $this->redirect($this->generateUrl('helpdesk_member_dashboard'));
        }

        return $this->render('@UVDeskExtensionFramework//dashboard.html.twig', []);
    }

    public function applicationsXHR(Request $request, ApplicationCollection $applications, ContainerInterface $container)
    {
        $assetsManager = $container->get('uvdesk_extension.assets_manager');

        $collection = array_map(function ($application) use ($assetsManager) {
            $metadata = $application->getMetadata();
            $packageMetadata = $application->getPackage()->getMetadata();

            return [
                'icon' => $assetsManager->getUrl($metadata->getIconPath()),
                'name' => $metadata->getName(),
                'qname' => $metadata->getQualifiedName(),
                'reference' => [
                    'vendor'  => $packageMetadata->getVendor(),
                    'package' => $packageMetadata->getPackage(),
                ],
            ];
        }, $applications->getCollection());

        return new JsonResponse($collection);
    }
}
