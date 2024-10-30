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

use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Qsomazzi\Particle\Traits\ActionHelperTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;

#[AsController]
final class ListAction
{
    use ActionHelperTrait;

    public function __invoke(
        Request $request,
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter] ?string $sort = null,
        #[MapQueryParameter] ?string $sortDirection = null,
        #[MapQueryParameter] ?string $query = null,
    ): Response {
        $admin      = $this->getAdmin($request->get('admin').'');
        $identifier = $request->get('identifier', $admin->getEntityClass()).'';

        // define Default vars
        $sort          = $sort ?? $admin->getDefaultSort();
        $sortDirection = $sortDirection ?? $admin->getDefaultSortDirection();

        // Ensure repository is ready for the crud
        $repository = $admin->getRepository();
        if (!method_exists($repository, 'findBySearchQueryBuilder')) {
            throw new \RuntimeException(sprintf('The repository %s must implement findBySearchQueryBuilder method', get_class($repository)));
        }

        $sort  = in_array($sort, $admin->getSortableFields()) ? $sort : $admin->getDefaultSort();
        $pager = Pagerfanta::createForCurrentPageWithMaxPerPage(
            new QueryAdapter($repository->findBySearchQueryBuilder($query, $sort, $sortDirection)),
            $page,
            $admin->getMaxPerPage()
        );

        return $this->render('@Particle/Action/index.html.twig', [
            'entities'      => $pager,
            'sort'          => $sort,
            'sortDirection' => $sortDirection,
            'admin'         => $admin,
            'identifier'    => $identifier,
        ]);
    }
}
