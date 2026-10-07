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
use Qsomazzi\Particle\Filter\FilterBuilder;
use Qsomazzi\Particle\Filter\FiltersDto;
use Qsomazzi\Particle\Metadata\Index;
use Qsomazzi\Particle\RequestPayload\IndexPayload;
use Qsomazzi\Particle\Traits\ActionHelperTrait;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;

#[AsController]
final class IndexAction
{
    use ActionHelperTrait;

    public function __invoke(
        ?Request                        $request,
        EntityManagerInterface          $entityManager,
        FormFactoryInterface            $formFactory,
        array                           $qsomazziParticleTemplates,
        #[MapQueryString] ?IndexPayload $payload,
    ): Response {
        $admin = $this->getAdmin($request->get('admin').'');

        // By default, if we don't have any query params, the payload is null, so we need to create a new one
        $payload = $payload ?? new IndexPayload();

        // Build Filters form
        $form = $formFactory->createNamed(FilterBuilder::NAME, FormType::class, FilterBuilder::cleanFilters($payload->filters), ['csrf_protection' => false]);
        FilterBuilder::buildFilters($form, $admin->getFields(), $admin->getDoctrineMetadata());
        $form->handleRequest($request);

        // If filtered parameters are different from the original, redirect
        if ($form->getData() !== $request->get('filters', [])) {
            return $this->redirectToRoute($admin->getOperationByType(Index::TYPE)->getName(), ['filters' => $form->getData()], Response::HTTP_SEE_OTHER);
        }

        $payload->filters = $form->getData();
        $entities = Pagerfanta::createForCurrentPageWithMaxPerPage(
            new QueryAdapter($admin->getQueryBuilder($entityManager, $payload)),
            $payload->page,
            $admin->getMetadata()->getMaxPerPage()
        );

        return $this->render($qsomazziParticleTemplates[Index::TYPE], [
            'entities'      => $entities,
            'sort'          => $payload->sort,
            'sortDirection' => $payload->sortDirection,
            'nbFilters'     => count($payload->filters),
            'admin'         => $admin,
            'form'          => $form->createView(),
        ]);
    }
}
