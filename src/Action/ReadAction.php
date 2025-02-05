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
use Qsomazzi\Particle\Metadata\Read;
use Qsomazzi\Particle\Traits\ActionHelperTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
final class ReadAction
{
    use ActionHelperTrait;

    public function __invoke(Request $request, EntityManagerInterface $em, array $qsomazziParticleTemplates): Response
    {
        $admin      = $this->getAdmin($request->get('admin').'');
        $identifier = $admin->getMetadata()->getIdentifier();

        $entity = $em->createQueryBuilder()
            ->select('e')
            ->from($admin->getMetadata()->getEntityClass(), 'e')
            ->where('e.'.$identifier.' = :'.$identifier)
            ->setParameter($identifier, $request->get($identifier))
            ->getQuery()
            ->getOneOrNullResult();

        return $this->render($qsomazziParticleTemplates[Read::TYPE], [
            'entity' => $entity,
            'admin'  => $admin,
        ]);
    }
}
