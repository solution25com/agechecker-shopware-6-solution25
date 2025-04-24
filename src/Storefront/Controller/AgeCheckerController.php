<?php

declare(strict_types=1);

namespace AgeChecker\Storefront\Controller;

use Shopware\Core\Framework\Context;
use AgeChecker\Service\AgeCheckerClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Shopware\Storefront\Controller\StorefrontController;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;

#[Route(defaults: ['_routeScope' => ['storefront']])]
class AgeCheckerController extends StorefrontController
{
    private EntityRepository $customerRepository;
    private AgeCheckerClient $ageCheckerClient;

    public function __construct(EntityRepository $customerRepository, AgeCheckerClient $ageCheckerClient)
    {
        $this->customerRepository = $customerRepository;
        $this->ageCheckerClient = $ageCheckerClient;
    }

    #[Route(
        path: '/age-checker-user-status',
        name: 'frontend.user-checker.status',
        methods: ['POST']
    )]
    public function updateAgeVerified(Request $request, SalesChannelContext $context): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $uuid = $data['uuid'];

        if(!$uuid) return new JsonResponse(['message' => 'uuid is required'], Response::HTTP_BAD_REQUEST);

        $response = $this->ageCheckerClient->request($uuid);

        $customer = $context->getCustomer();
        $customFields = $customer->getCustomFields();

        if ($response->status === 'accepted') {
            $customFields['custom_age_confirmed_'] = true;
        }

        $customer->setCustomFields($customFields);

        $this->customerRepository->update(
            [
                [
                    'id' => $customer->getId(),
                    'customFields' => $customFields,
                ]
            ],
            $context->getContext()
        );

        return new JsonResponse(['message' => 'Customer age verification status updated']);
    }

    #[Route(
        path: '/temporary-denied',
        name: 'frontend.user-checker.temporary-denied',
        methods: ['GET']
    )]
    public function denyTemporary(Request $request, SalesChannelContext $context): Response
    {
        return $this->renderStorefront('@Storefront/storefront/page/checkout/temporary-denied.html.twig');
    }
}
