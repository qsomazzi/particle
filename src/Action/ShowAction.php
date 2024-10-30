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

use Qsomazzi\Particle\Traits\ActionHelperTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
final class ShowAction
{
    use ActionHelperTrait;

    public function __invoke(Request $request, string $id): Response
    {
        $admin      = $this->getAdmin($request->get('admin').'');
        $identifier = $request->get('identifier', $admin->getEntityClass()).'';
        $entity     = $admin->getRepository()->find($id);

        return $this->render('@Particle/Action/show.html.twig', [
            'entity'     => $entity,
            'identifier' => $identifier,
            'admin'      => $admin,
        ]);
    }
}
