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

use Qsomazzi\Particle\Model\Notification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
final class HomepageAction extends AbstractController
{
    public function __invoke(Request $request, array $qsomazziParticleTemplates): Response
    {
        $session       = $request->getSession();
        $notifications = $session->get('notifications');

        if (is_null($notifications)) {
            $session->set('notifications', [
                new Notification(
                    title: 'Welcome on Aragorn !',
                    description: 'This project is still under development, feel free to check our Wiki for more infos',
                    link: 'https://gitlab.ekino.com/php-labs/aragorn/-/wikis/home',
                    color: 'green'
                ),
            ]);
        }

        return $this->render($qsomazziParticleTemplates['homepage']);
    }
}
