<?php

declare(strict_types=1);

/*
 * This file is part of the Particle project.
 *
 * (c) Qsomazzi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Qsomazzi\Particle\Action;

use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Qsomazzi\Particle\Metadata\Index;
use Qsomazzi\Particle\RequestPayload\IndexPayload;
use Qsomazzi\Particle\Traits\ActionHelperTrait;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;

#[AsController]
final class IndexAction
{
    use ActionHelperTrait;

    public function __invoke(
        Request                         $request,
        EntityManagerInterface          $entityManager,
        FormFactoryInterface            $formFactory,
        array                           $qsomazziParticleTemplates,
        #[MapQueryString] ?IndexPayload $payload,
    ): Response {
        $admin = $this->getAdmin($request->get('admin').'');

        // By default, if we don't have any query params, the payload is null, so we need to create a new one
        $payload = $payload ?? new IndexPayload();

        // Build Filters form
        $form = $formFactory->createNamed(
            name: 'filters',
            data: $payload->filters,
            options: [
                'method'           => 'GET',
                'action'           => $request->getUri(),
                'attr' => [
                    'data-turbo-frame' => sprintf('%s-list', $admin->getMetadata()->getShortName()),
                ]
            ]
        );
        foreach ($admin->getFields() as $field) {
            if ($field->isFilterable()) {
                $form->add(sprintf('%s:type', $field->getKey()), ChoiceType::class, [
                    'label'    => $field->getLabel(),
                    'required' => false,
                    'placeholder' => false,
                    'choices'  => [
                        'Contains' => 'contains',
                        'Equals'   => 'equals',
                        'Starts with' => 'starts_with',
                        'Ends with' => 'ends_with',
                    ],
                ]);
                $form->add(sprintf('%s:value', $field->getKey()), null, ['required' => false, 'label' => false]);
            }
        }
        $form->add('submit', SubmitType::class, [
            'label' => 'Apply filters',
            'attr'  => [
                'data-bs-dismiss' => 'offcanvas',
                'class'           => 'btn btn-primary',
            ],
        ]);
        $form->add('clear', SubmitType::class, [
            'label' => 'Clear filters',
            'attr'  => [
                'data-bs-dismiss' => 'offcanvas',
                'class'           => 'ms-auto btn btn-danger',
            ],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $payload->buildFilters();
        }

        $entities = Pagerfanta::createForCurrentPageWithMaxPerPage(
            new QueryAdapter($admin->getQueryBuilder($entityManager, $payload)),
            $payload->page,
            $admin->getMetadata()->getMaxPerPage()
        );

        return $this->render($qsomazziParticleTemplates[Index::TYPE], [
            'entities'      => $entities,
            'sort'          => $payload->sort,
            'sortDirection' => $payload->sortDirection,
            'filters'       => $payload->filters,
            'admin'         => $admin,
            'form'          => $form->createView(),
        ]);
    }
}
