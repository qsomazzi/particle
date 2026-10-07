<?php

namespace Qsomazzi\Particle\Filter;

use Doctrine\ORM\Mapping\ClassMetadata;
use Qsomazzi\Particle\Component\Fields\FieldInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;

final class FilterBuilder
{
    public const string NAME = 'filters';

    public const string STRING_FILTER_EQUALS = 'equals';
    public const string STRING_FILTER_CONTAINS = 'contains';
    public const string STRING_FILTER_STARTS_WITH = 'starts_with';
    public const string STRING_FILTER_ENDS_WITH = 'ends_with';

    public const string DATE_FILTER_EQUALS = 'equals';
    public const string DATE_FILTER_BEFORE = 'before';
    public const string DATE_FILTER_AFTER = 'after';

    public const string INTEGER_FILTER_EQUALS = 'equals';
    public const string INTEGER_FILTER_GT = 'gt';
    public const string INTEGER_FILTER_LT = 'lt';

    public static function buildFilters(FormInterface $form, array $fields, ClassMetadata $doctrineMetadata): void
    {
        foreach ($fields as $field) {
            if ($field->isFilterable()) {
                self::guessFilterField($form, $field, $doctrineMetadata);
            }
        }
        $form->add('submit', SubmitType::class);
        $form->add('clear', SubmitType::class);
    }

    public static function cleanFilters(array $filters): array
    {
        return array_filter($filters, function ($value, $key) {
            if (str_ends_with($key, ':type') && $value === 'equals') {
                return false;
            }

            return $value !== null && $value !== '';
        }, ARRAY_FILTER_USE_BOTH);
    }

    private static function guessFilterField(FormInterface $form, FieldInterface $field, $doctrineMetadata): void
    {
        $fieldMapping = $doctrineMetadata->getFieldMapping($field->getKey());

        $data  = $form->getData();
        $type  = sprintf('%s:type', $field->getKey());
        $value = sprintf('%s:value', $field->getKey());

        switch ($fieldMapping['type']) {
            case 'string':
            case 'text':
                $form->add($type, ChoiceType::class, [
                    'label'       => $field->getLabel(),
                    'required'    => false,
                    'placeholder' => false,
                    'data'        => $data[$type] ?? null,
                    'choices'     => [
                        'Equals'      => self::STRING_FILTER_EQUALS,
                        'Contains'    => self::STRING_FILTER_CONTAINS,
                        'Starts with' => self::STRING_FILTER_STARTS_WITH,
                        'Ends with'   => self::STRING_FILTER_ENDS_WITH,
                    ],
                ]);
                $form->add($value, TextType::class, [
                    'required' => false,
                    'label'    => false,
                    'data'     => $data[$value] ?? null,
                ]);
                break;
            case 'datetime_immutable':
                $form->add($type, ChoiceType::class, [
                    'label'       => $field->getLabel(),
                    'required'    => false,
                    'placeholder' => false,
                    'data'        => $data[$type] ?? null,
                    'choices'  => [
                        'Equals'  => self::DATE_FILTER_EQUALS,
                        'Before'  => self::DATE_FILTER_BEFORE,
                        'After'   => self::DATE_FILTER_AFTER,
                    ],
                ]);
                $form->add($value, DateTimeType::class, [
                    'required' => false,
                    'label'    => false,
                    'widget'   => 'single_text',
                    'data'     => isset($data[$value]) && $data[$value] !== '' ? new \DateTimeImmutable($data[$value]) : null,
                ]);
                break;

            case 'integer':
                $form->add($type, ChoiceType::class, [
                    'label'       => $field->getLabel(),
                    'required'    => false,
                    'placeholder' => false,
                    'data'        => $data[$type] ?? null,
                    'choices'     => [
                        'Equals'       => self::INTEGER_FILTER_EQUALS,
                        'Greater than' => self::INTEGER_FILTER_GT,
                        'Less than'    => self::INTEGER_FILTER_LT,
                    ],
                ]);
                $form->add($value, NumberType::class, [
                    'required' => false,
                    'label'    => false,
                    'data'     => isset($data[$value]) && $data[$value] !== '' ? $data[$value] : null,
                ]);
                break;

            case 'boolean':
                $form->add($value, ChoiceType::class, [
                    'required' => false,
                    'label'    => $field->getLabel(),
                    'data'     => $data[$value] ?? null,
                    'choices' => [
                        'Yes' => true,
                        'No'  => false,
                    ],
                ]);
                break;

            default:
                throw new \Exception(sprintf('Field type %s not handled with autoconfiguration, please configure it manually', $fieldMapping['type']));
        }
    }
}