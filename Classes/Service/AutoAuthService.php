<?php

declare(strict_types=1);

namespace Site\Backend\Service;

use TYPO3\CMS\Core\Authentication\AbstractAuthenticationService;

/**
 * Auto login the configured user.
 */
class AutoAuthService extends AbstractAuthenticationService
{
    /**
     * The name of the user to be fetched.
     *
     * @var string
     */
    private $username = 'admin';

    /**
     * Fetch the user by its username.
     *
     * @return string|void
     */
    public function getUser()
    {
        return $this->fetchUserRecord($this->username);
    }

    /**
     * Force-always authenticating the user by returning a 200.
     *
     * @return int
     */
    public function authUser(array $user)
    {
        return 200;
    }
}
