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
final class AddAction
{
    use ActionHelperTrait;

    public function __invoke(Request $request, EntityManagerInterface $entityManager): Response
    {
        $admin      = $this->getAdmin($request->get('admin').'');
        $entity     = new ($admin->getEntityClass())();
        $form       = $this->createForm($admin->getFormClass(), $entity);
        $identifier = $request->get('identifier', $admin->getEntityClass()).'';

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($entity);
            $entityManager->flush();

            $this->addFlash('success', sprintf('%s added with success !', $admin->getSingularLabel()));

            if (method_exists($entity, 'getId') && $request->get('submit') !== 'submit_and_close') {
                return $this->redirectToRoute(sprintf('admin_%s_edit', $identifier), ['id' => $entity->getId()], Response::HTTP_SEE_OTHER);
            }

            return $this->redirectToRoute(sprintf('admin_%s', $identifier), [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('@Particle/Action/new.html.twig', [
            'entity'     => $entity,
            'form'       => $form->createView(),
            'identifier' => $identifier,
            'admin'      => $admin,
        ]);
    }
}
