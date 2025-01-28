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

        list ($sort, $sortDirection) = $admin->getSort($sort, $sortDirection);

        $repository = $admin->getRepository();
        if (!method_exists($repository, 'findBySearchQueryBuilder')) {
            $qb = $repository->createQueryBuilder('e');

            if (!is_null($query)) {
                $qb
                    ->where('LOWER(e.'.$admin->getDefaultSearchColumn().') LIKE :query')
                    ->setParameter('query', '%'.strtolower($query).'%')
                ;
            }

            $qb->orderBy('e.'.$sort, $sortDirection);
        } else {
            $qb = $repository->findBySearchQueryBuilder($query, $sort, $sortDirection);
        }

        $pager = Pagerfanta::createForCurrentPageWithMaxPerPage(
            new QueryAdapter($qb),
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
