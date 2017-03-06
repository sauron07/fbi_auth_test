<?php

namespace Matvieiev\LoginBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{

    /**
     * Handle login request.
     *
     * @return Response
     */
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('@MatvieievLogin/Default/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
}
