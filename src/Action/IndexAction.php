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
        array                           $qsomazziParticleTemplates,
        #[MapQueryString] ?IndexPayload $payload
    ): Response {
        $admin = $this->getAdmin($request->get('admin').'');

        // By default, if we don't have any query params, the payload is null, so we need to create a new one
        $payload = $payload ?? new IndexPayload();

        $entities = Pagerfanta::createForCurrentPageWithMaxPerPage(
            new QueryAdapter($admin->getQueryBuilder($entityManager, $payload)),
            $payload->page,
            $admin->getMetadata()->getMaxPerPage()
        );

        return $this->render($qsomazziParticleTemplates[Index::TYPE], [
            'entities'      => $entities,
            'sort'          => $payload->sort,
            'sortDirection' => $payload->sortDirection,
            'admin'         => $admin,
        ]);
    }
}
