<?php
/**
 * Created by mcfedr on 27/06/15 14:15
 */

namespace Ekreative\RedmineLoginBundle\Controller;

use Ekreative\RedmineLoginBundle\Form\LoginType;
use Mcfedr\JsonFormBundle\Controller\JsonController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;

class LoginController extends JsonController
{
    /**
     * @Route("/login", name="login")
     * @Method({"GET"})
     * @Template()
     * @param Request $request
     * @return array
     */
    public function loginAction(Request $request)
    {
        $session = $request->getSession();

        $form = $this->createForm(new LoginType(), [
            'username' => $session->get(Security::LAST_USERNAME)
        ], [
            'action' => $this->generateUrl('login_check')
        ]);
        $form->add('submit', 'submit', ['label' => 'Sign In']);

        // get the login error if there is one
        if ($request->attributes->has(Security::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                Security::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(Security::AUTHENTICATION_ERROR);
            $session->remove(Security::AUTHENTICATION_ERROR);
        }

        return [
            'last_username' => $session->get(Security::LAST_USERNAME),
            'error' => $error,
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/login")
     * @Method({"POST"})
     * @ApiDoc(
     *   description="Get the users api key",
     *   resource=true,
     *   input="Ekreative\RedmineLoginBundle\Form\LoginType",
     *   statusCodes={
     *     401={"Invalid username or password"}
     *   }
     * )
     */
    public function apiLoginAction(Request $request)
    {
        $form = $this->createForm(new LoginType());
        $this->handleJsonForm($form, $request);
        $data = $form->getData();

        try {
            $user = $this->get('ekreative_redmine_login.provider')->getUserForUsernamePassword(
                $data['username'],
                $data['password']
            );
        }
        catch (AuthenticationException $e) {
            throw new UnauthorizedHttpException(null);
        }

        return new JsonResponse([
            'user' => $user
        ]);
    }
}
