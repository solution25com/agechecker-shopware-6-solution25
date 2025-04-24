<?php declare(strict_types=1);

namespace AgeChecker\Service;

use Shopware\Core\Defaults;
use Shopware\Core\Framework\Context;
use Shopware\Core\System\CustomField\CustomFieldTypes;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;

class CustomFieldsInstaller
{
    private const CUSTOM_FIELDSET_NAME = 'customer_age_confirmed_set';

    private const CUSTOM_FIELDSET = [
        'name' => self::CUSTOM_FIELDSET_NAME,
        'config' => [
            'label' => [
                'en-GB' => 'Age Confirmation for Customer',
                'de-DE' => 'Bestätigung des Alters für Kunden',
                Defaults::LANGUAGE_SYSTEM => 'Age Confirmation for Customer'
            ]
        ],
        'customFields' => [
            [
                'name' => 'custom_age_confirmed_',
                'type' => CustomFieldTypes::BOOL,
                'config' => [
                    'label' => [
                        'en-GB' => 'Age Confirmed by Customer',
                        'de-DE' => 'Alter vom Kunden bestätigt',
                        Defaults::LANGUAGE_SYSTEM => 'Age Confirmed by Customer'
                    ],
                    'customFieldPosition' => 1,
                    'helpText' => [
                        'en-GB' => 'Indicates if the customer has confirmed their age.',
                        'de-DE' => 'Gibt an, ob der Kunde sein Alter bestätigt hat.',
                        Defaults::LANGUAGE_SYSTEM => 'Indicates if the customer has confirmed their age.'
                    ]
                ]
            ]
        ]
    ];

    public function __construct(
        private readonly EntityRepository $customFieldSetRepository,
        private readonly EntityRepository $customFieldSetRelationRepository,
        private readonly EntityRepository $customFieldRepository
    ) {
    }

    public function install(Context $context): void
    {
        $this->customFieldSetRepository->upsert([
            self::CUSTOM_FIELDSET
        ], $context);
    }

    public function uninstall(Context $context): void
    {
        $customFieldSetId = $this->getCustomFieldSetId($context);

        if ($customFieldSetId) {
            $this->deleteCustomFields($context);

            $this->customFieldSetRepository->delete([
                ['id' => $customFieldSetId]
            ], $context);
        }
    }

    public function addRelations(Context $context): void
    {
        $this->customFieldSetRelationRepository->upsert(array_map(function (string $customFieldSetId) {
            return [
                'customFieldSetId' => $customFieldSetId,
                'entityName' => 'customer',
            ];
        }, $this->getCustomFieldSetIds($context)), $context);
    }

    /**
     * @return string[]
     */
    private function getCustomFieldSetIds(Context $context): array
    {
        $criteria = new Criteria();

        $criteria->addFilter(new EqualsFilter('name', self::CUSTOM_FIELDSET_NAME));

        return $this->customFieldSetRepository->searchIds($criteria, $context)->getIds();
    }

    /**
     * Get the custom field set ID.
     */
    private function getCustomFieldSetId(Context $context): ?string
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', self::CUSTOM_FIELDSET_NAME));

        $ids = $this->customFieldSetRepository->searchIds($criteria, $context)->getIds();
        return !empty($ids) ? $ids[0] : null;
    }

    /**
     * Delete custom fields associated with the fieldset.
     */
    private function deleteCustomFields(Context $context): void
    {
        $customFieldId = $this->getCustomFieldId($context);

        if ($customFieldId) {
            $this->customFieldRepository->delete([
                ['id' => $customFieldId]
            ], $context);
        }
    }

    /**
     * Get the custom field ID.
     */
    private function getCustomFieldId(Context $context): ?string
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', 'custom_age_confirmed'));

        $ids = $this->customFieldRepository->searchIds($criteria, $context)->getIds();
        return !empty($ids) ? $ids[0] : null;
    }
}
