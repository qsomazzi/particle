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
use Qsomazzi\Particle\Utils\Guesser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
final class ReadAction
{
    use ActionHelperTrait;

    public function __invoke(Request $request): Response
    {
        $admin      = $this->getAdmin($request->get('admin').'');
        $identifier = $admin->getMetadata()->getIdentifier();
        $entity     = $admin->getRepository()->findOneBy([$identifier => $request->get($identifier)]);

        return $this->render('@Particle/Action/show.html.twig', [
            'entity' => $entity,
            'admin'  => $admin,
        ]);
    }
}
