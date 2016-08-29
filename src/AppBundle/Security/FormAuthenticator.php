<?php

namespace AppBundle\Security;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class FormAuthenticator extends AbstractFormLoginAuthenticator
{
    /** @var  EntityManager */
    private $em;

    /** @var  UrlGeneratorInterface */
    private $urlGenerator;

    /** @var  Session */
    private $session;

    /**
     * FormAuthenticator constructor.
     * @param EntityManager         $em
     * @param UrlGeneratorInterface $urlGenerator
     * @param Session               $session
     */
    public function __construct(EntityManager $em, UrlGeneratorInterface $urlGenerator, Session $session)
    {
        $this->em           = $em;
        $this->urlGenerator = $urlGenerator;
        $this->session      = $session;
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate('login');
    }

    /**
     * The user will be redirected to the secure page they originally tried
     * to access. But if no such page exists (i.e. the user went to the
     * login page directly), this returns the URL the user should be redirected
     * to after logging in successfully (e.g. your homepage).
     *
     * @return string
     */
    protected function getDefaultSuccessRedirectUrl()
    {
        return $this->urlGenerator->generate('admin_dashboard');
    }

    public function getCredentials(Request $request)
    {
        $requestPathInfo = $request->getPathInfo();
        $requestMethod   = $request->getMethod();

        if ($requestPathInfo != '/login') {
            return null;
        }

        if (strcasecmp($requestMethod, 'POST') != 0) {
            return null;
        }

        $email    = $request->request->get('email');
        $password = $request->request->get('password');
        $request->getSession()
                ->set(Security::LAST_USERNAME, $email);

        return array(
            'email'    => $email,
            'password' => $password
        );
    }

    /**
     * Return a UserInterface object based on the credentials.
     *
     * The *credentials* are the return value from getCredentials()
     *
     * You may throw an AuthenticationException if you wish. If you return
     * null, then a UsernameNotFoundException is thrown for you.
     *
     * @param mixed                 $credentials
     * @param UserProviderInterface $userProvider
     *
     * @throws AuthenticationException
     *
     * @return UserInterface|null
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $email = $credentials['email'];

        try {
            return $this->em->getRepository('AppBundle:User')
                            ->findOneBy(['email' => $email]);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Returns true if the credentials are valid.
     *
     * If any value other than true is returned, authentication will
     * fail. You may also throw an AuthenticationException if you wish
     * to cause authentication to fail.
     *
     * The *credentials* are the return value from getCredentials()
     *
     * @param mixed         $credentials
     * @param UserInterface $user
     *
     * @return bool
     *
     * @throws AuthenticationException
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        $requestPassword = $credentials['password'];
        $userPassword    = $user->getPassword();

        if (password_verify($requestPassword, $userPassword)) {
            return true;
        }

        throw new AuthenticationException('Invalid email or password');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $url = $this->urlGenerator->generate('admin_dashboard');

        $response = new JsonResponse();
        $response->setData(
            [
                'url'    => $url,
                'action' => 'redirect'
            ]
        );

        return $response;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $response = new JsonResponse();
        $response->setStatusCode(Response::HTTP_UNAUTHORIZED);

        $request->getSession()
                ->set(Security::AUTHENTICATION_ERROR, $exception);

        if (is_a($exception, UsernameNotFoundException::class)) {
            $response->setData(
                [
                    'action' => 'login',
                    'message' => "Email not found"
                ]
            );
        }

        else {
            $response->setData(
                [
                    'action'  => 'login',
                    'message' => 'Invalid email or password'
                ]
            );
        }

        return $response;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $redirectUrl = $request->get('dest');

        $this->session->set('redirectUrl', $redirectUrl);

        return new RedirectResponse('/login');
    }
}