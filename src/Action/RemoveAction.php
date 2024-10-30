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
use Qsomazzi\Particle\Traits\ActionHelperTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
final class RemoveAction
{
    use ActionHelperTrait;

    public function __invoke(Request $request, EntityManagerInterface $entityManager, string $id): Response
    {
        $admin      = $this->getAdmin($request->get('admin').'');
        $identifier = $request->get('identifier', $admin->getEntityClass()).'';
        $entity     = $admin->getRepository()->find($id);

        if ($entity !== null && method_exists($entity, 'getId')) {
            if ($this->isCsrfTokenValid('delete'.$entity->getId(), $request->getPayload()->getString('_token'))) {
                $entityManager->remove($entity);
                $entityManager->flush();

                $this->addFlash('success', sprintf('%s removed with success !', $admin->getSingularLabel()));
            } else {
                $this->addFlash('error', 'Invalid CSRF token');
            }
        }

        return $this->redirectToRoute(sprintf('admin_%s', $identifier), [], Response::HTTP_SEE_OTHER);
    }
}
