<?php

namespace ConsulConfigManager\Auth\Services;

use Illuminate\Support\Facades\Auth;
use ConsulConfigManager\Auth\Http\Requests\AuthRequest;
use ConsulConfigManager\Users\Interfaces\UserInterface;
use ConsulConfigManager\Users\ValueObjects\EmailValueObject;
use ConsulConfigManager\Users\ValueObjects\PasswordValueObject;
use ConsulConfigManager\Users\ValueObjects\UsernameValueObject;
use ConsulConfigManager\Users\Interfaces\UserRepositoryInterface;

/**
 * Class Authenticator
 *
 * @package ConsulConfigManager\Auth\Services
 */
class Authenticator
{
    /**
     * User repository instance
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $repository;

    /**
     * Authenticator Constructor.
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->repository = $userRepository;
    }

    /**
     * Attempt to authenticate user with given credentials
     * @param AuthRequest $request
     *
     * @return UserInterface|null
     */
    public function attempt(AuthRequest $request): ?UserInterface
    {
        $login = trim($request->get('emailOrUsername'));
        $password = trim($request->get('password'));

        if (str_contains($login, '@')) {
            return $this->emailAuthentication($login, $password);
        }
        return $this->usernameAuthentication($login, $password);
    }

    /**
     * Perform authentication using e-mail
     * @param string $email
     * @param string $password
     *
     * @return UserInterface|null
     */
    protected function emailAuthentication(string $email, string $password): ?UserInterface
    {
        $ldapAuthenticated = $this->ldapEmailAuthentication($email, $password);
        if ($ldapAuthenticated) {
            return $ldapAuthenticated;
        }
        return $this->localEmailAuthentication($email, $password);
    }

    /**
     * Authenticate user through LDAP using e-mail
     * @param string $email
     * @param string $password
     *
     * @return UserInterface|null
     */
    protected function ldapEmailAuthentication(string $email, string $password): ?UserInterface
    {
        return $this->ldapAuthentication($email, $password, 'userPrincipalName');
    }

    /**
     * Authenticate user locally using e-mail
     * @param string $email
     * @param string $password
     *
     * @return UserInterface|null
     */
    protected function localEmailAuthentication(string $email, string $password): ?UserInterface
    {
        return $this->localAuthentication(new EmailValueObject($email), $password);
    }

    /**
     * Perform authentication using username
     * @param string $username
     * @param string $password
     *
     * @return UserInterface|null
     */
    protected function usernameAuthentication(string $username, string $password): ?UserInterface
    {
        $ldapAuthenticated = $this->ldapUsernameAuthentication($username, $password);
        if ($ldapAuthenticated) {
            return $ldapAuthenticated;
        }
        return $this->localUsernameAuthentication($username, $password);
    }

    /**
     * Authenticate user through LDAP using username
     * @param string $username
     * @param string $password
     *
     * @return UserInterface|null
     */
    protected function ldapUsernameAuthentication(string $username, string $password): ?UserInterface
    {
        return $this->ldapAuthentication($username, $password, 'sAMAccountName');
    }

    /**
     * Authenticate user locally using username
     * @param string $username
     * @param string $password
     *
     * @return UserInterface|null
     */
    protected function localUsernameAuthentication(string $username, string $password): ?UserInterface
    {
        return $this->localAuthentication(new UsernameValueObject($username), $password);
    }

    /**
     * Common LDAP authentication method
     * @param string $login
     * @param string $password
     * @param string $type
     *
     * @return UserInterface|null
     */
    protected function ldapAuthentication(string $login, string $password, string $type): ?UserInterface
    {
        if (!Auth::attempt([
            $type       =>  $login,
            'password'  =>  $password,
        ])) {
            return null;
        }
        return Auth::user();
    }

    /**
     * Common LOCAL authentication method
     * @param EmailValueObject|UsernameValueObject $login
     * @param string                               $password
     *
     * @return UserInterface|null
     */
    protected function localAuthentication(EmailValueObject|UsernameValueObject $login, string $password): ?UserInterface
    {
        if ($login instanceof EmailValueObject) {
            $user = $this->repository->findByEmail($login);
        } else {
            $user = $this->repository->findByUsername($login);
        }

        if (!$user) {
            return null;
        }

        if (!$user->getPassword()->check(new PasswordValueObject($password))) {
            return null;
        }
        return $user;
    }
}
