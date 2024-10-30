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

namespace Qsomazzi\Particle\Traits;

use Qsomazzi\Particle\Admin\AdminInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Exception\SessionNotFoundException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\FlashBagAwareSessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Contracts\Service\Attribute\Required;
use Twig\Environment;

trait ActionHelperTrait
{
    #[Required]
    public Environment $twig;
    #[Required]
    public RouterInterface $router;
    #[Required]
    public FormFactoryInterface $formFactory;
    #[Required]
    public CsrfTokenManagerInterface $csrfTokenManager;
    #[Required]
    public RequestStack $requestStack;
    public iterable $admins;

    #[Required]
    public function setAdmins(#[AutowireIterator('app.admin')] iterable $admins): void
    {
        $this->admins = $admins;
    }

    public function render(string $template, array $context = []): Response
    {
        $response = new Response();
        $content  = $this->twig->render($template, $context);

        $response->setContent($content);
        $response->setPublic();
        $response->setStatusCode(Response::HTTP_OK);
        $response->setSharedMaxAge(600);

        return $response;
    }

    public function redirectToRoute(string $route, array $parameters = [], int $status = Response::HTTP_FOUND): Response
    {
        $path = $this->router->generate($route, $parameters);

        return new RedirectResponse($path, $status);
    }

    public function createForm(string $type, mixed $data = null, array $options = []): FormInterface
    {
        return $this->formFactory->create($type, $data, $options);
    }

    public function isCsrfTokenValid(string $id, #[\SensitiveParameter] ?string $token): bool
    {
        return $this->csrfTokenManager->isTokenValid(new CsrfToken($id, $token));
    }

    protected function addFlash(string $type, mixed $message): void
    {
        try {
            $session = $this->requestStack->getSession();
        } catch (SessionNotFoundException $e) {
            throw new \LogicException('You cannot use the addFlash method if sessions are disabled. Enable them in "config/packages/framework.yaml".', 0, $e);
        }

        if ($session instanceof FlashBagAwareSessionInterface) {
            $session->getFlashBag()->add($type, $message);
        }
    }

    public function getAdmin(string $adminClass): AdminInterface
    {
        foreach ($this->admins as $admin) {
            if ($admin::class === $adminClass) {
                return $admin;
            }
        }

        throw new \RuntimeException(sprintf('Admin class %s not found', $adminClass));
    }
}
