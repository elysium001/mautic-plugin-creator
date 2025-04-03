<?php

namespace MauticPlugin\ExampleBundle\Controller;

use Mautic\CoreBundle\Controller\CommonController;
use MauticPlugin\ExampleBundle\Services\DemoService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends CommonController
{
    public function fetchViewAction(DemoService $demoService)
    {
        $people        = $demoService->getMauticContactCount();

        return $this->delegateView(
            [
                'viewParameters'  => [
                    'title'               => 'Mautic Contact Service',
                    'mautic_contacts'     => $people ?? [],
                ],
                'contentTemplate' => '@ExampleBundle/DemoViews/fetch.html.twig',
                'passthroughVars' => [
                    'activeLink'    => '#plugin_example_sync_fetch_view',
                    'route'         => $this->generateUrl('plugin_example_sync_fetch_view'),
                    'mauticContent' => 'helloWorldDetails',
                ],
            ]
        );
    }

    public function fetchDogsAction(Request $request, DemoService $demoService)
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(['error' => 'Invalid request'], 400);
        }

        try {
            $dogs = $demoService->callExternalService();

            return new JsonResponse(
                [
                    'dogs'    => $dogs,
                ]
            );
        } catch (\Throwable $th) {
            return new Response("From controller - $th", 400);
        }
    }
}
