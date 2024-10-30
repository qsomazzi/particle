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
final class EditAction
{
    use ActionHelperTrait;

    public function __invoke(Request $request, EntityManagerInterface $entityManager, string $id): Response
    {
        $admin      = $this->getAdmin($request->get('admin').'');
        $identifier = $request->get('identifier', $admin->getEntityClass()).'';
        $entity     = $admin->getRepository()->find($id);

        $form = $this->createForm($admin->getFormClass(), $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', sprintf('%s updated with success !', $admin->getSingularLabel()));

            if ($entity !== null && method_exists($entity, 'getId') && $request->get('submit') !== 'submit_and_close') {
                return $this->redirectToRoute(sprintf('admin_%s_edit', $identifier), ['id' => $entity->getId()], Response::HTTP_SEE_OTHER);
            }

            return $this->redirectToRoute(sprintf('admin_%s', $identifier), [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('@Particle/Action/edit.html.twig', [
            'entity'     => $entity,
            'form'       => $form->createView(),
            'identifier' => $identifier,
            'admin'      => $admin,
        ]);
    }
}
