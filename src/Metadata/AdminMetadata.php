<?php

namespace Qsomazzi\Particle\Metadata;

use Qsomazzi\Particle\Attributes\Admin;
use Qsomazzi\Particle\Utils\Guesser;

final readonly class AdminMetadata
{
    public function __construct(
        private string $entityClass,
        private string $formClass,
        private string $identifier,
        private int    $maxPerPage,
        private string $prefix,
        private string $domain,
        private string $singularLabel,
        private string $pluralLabel,
        private string $shortName,
        private string $defaultSortColumn,
        private string $defaultSortDirection,
        private array  $defaultSearchColumns,
        private array  $operations
    ) {
    }

    public static function mapFromAttributes(Admin $attribute): array
    {
        // Guess some content
        $guesses = Guesser::getLabels($attribute->getEntityClass());

        $domain     = ucfirst($attribute->getDomain() ?? $guesses['domain']);
        $shortName  = strtolower($attribute->getShortName() ?? $guesses['shortName']);
        $prefix     = $attribute->getPrefix();
        $identifier = $attribute->getIdentifier();

        return [
            'entityClass'          => $attribute->getEntityClass(),
            'formClass'            => $attribute->getFormClass(),
            'identifier'           => $identifier,
            'maxPerPage'           => $attribute->getMaxPerPage(),
            'prefix'               => $prefix,
            'domain'               => $domain,
            'singularLabel'        => ucfirst($attribute->getSingularLabel() ?? $guesses['singularLabel']),
            'pluralLabel'          => ucfirst($attribute->getPluralLabel() ?? $guesses['pluralLabel']),
            'shortName'            => $shortName,
            'defaultSortColumn'    => $attribute->getDefaultSortColumn(),
            'defaultSortDirection' => $attribute->getDefaultSortDirection(),
            'defaultSearchColumns' => $attribute->getDefaultSearchColumns(),
            'operations'           => self::buildOperations($prefix, $domain, $shortName, $identifier, $attribute->getOperations()),
        ];
    }

    public function getEntityClass(): string
    {
        return $this->entityClass;
    }

    public function getFormClass(): string
    {
        return $this->formClass;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getMaxPerPage(): int
    {
        return $this->maxPerPage;
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function getSingularLabel(): string
    {
        return $this->singularLabel;
    }

    public function getPluralLabel(): string
    {
        return $this->pluralLabel;
    }

    public function getShortName(): string
    {
        return $this->shortName;
    }

    public function getDefaultSortColumn(): string
    {
        return $this->defaultSortColumn;
    }

    public function getDefaultSortDirection(): string
    {
        return $this->defaultSortDirection;
    }

    public function getDefaultSearchColumns(): array
    {
        return $this->defaultSearchColumns;
    }

    public function getOperations(): array
    {
        return $this->operations;
    }

    private static function buildOperations(
        string $prefix,
        string $domain,
        string $shortName,
        string $identifier,
        ?array $operations,
    ): array
    {
        $formattedOperations = [];
        $operations          = $operations ?? [Index::class, Read::class, Create::class, Update::class, Delete::class];
        foreach ($operations as $one) {
            if (is_array($one) && isset($one['class'])) {
                $operation = $one['class']::buildFromConfig($one, $prefix, $domain, $shortName, $identifier);
            } else {
                $operation = $one::buildFromGuesser($prefix, $domain, $shortName, $identifier);
            }

            $formattedOperations[] = $operation->toArray();
        }

        return $formattedOperations;
    }
}