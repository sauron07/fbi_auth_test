<?php

namespace Matvieiev\LoginBundle\Service;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Twig_Environment;

/**
 * Class AccessDeniedHandler
 *
 * @package Matvieiev\LoginBundle\Service
 */
class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * AccessDeniedHandler constructor.
     *
     * @param Twig_Environment $twig Twig environment.
     */
    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Handles an access denied failure.
     *
     * @param Request               $request
     * @param AccessDeniedException $accessDeniedException
     *
     * @return Response may return null
     */
    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        $response = new Response($this->twig->render('@MatvieievLogin/Default/access_denied.html.twig'));
        return $response;
    }
}