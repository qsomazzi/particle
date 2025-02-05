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
use Qsomazzi\Particle\Metadata\Create;
use Qsomazzi\Particle\Metadata\Index;
use Qsomazzi\Particle\Metadata\Update;
use Qsomazzi\Particle\Traits\ActionHelperTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
final class CreateAction
{
    use ActionHelperTrait;

    public function __invoke(Request $request, EntityManagerInterface $entityManager, array $qsomazziParticleTemplates): Response
    {
        $admin      = $this->getAdmin($request->get('admin').'');
        $identifier = $admin->getMetadata()->getIdentifier();
        $entity     = new ($admin->getMetadata()->getEntityClass())();
        $form       = $this->createForm($admin->getMetadata()->getFormClass(), $entity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($entity);
            $entityManager->flush();

            $this->addFlash('success', sprintf('%s added with success !', $admin->getMetadata()->getSingularLabel()));

            if ($request->get('submit') !== 'submit_and_close') {
                $get = 'get'.ucwords($identifier);

                return $this->redirectToRoute($admin->getOperationByType(Update::TYPE)->getName(), [$identifier => $entity->$get()], Response::HTTP_SEE_OTHER);
            }

            return $this->redirectToRoute($admin->getOperationByType(Index::TYPE)->getName(), [], Response::HTTP_SEE_OTHER);
        }

        return $this->render($qsomazziParticleTemplates[Create::TYPE], [
            'entity' => $entity,
            'form'   => $form->createView(),
            'admin'  => $admin,
        ]);
    }
}
