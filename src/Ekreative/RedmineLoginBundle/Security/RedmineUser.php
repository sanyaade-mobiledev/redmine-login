<?php
/**
 * Created by mcfedr on 27/06/15 11:30
 */

namespace Ekreative\RedmineLoginBundle\Security;

use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class RedmineUser implements UserInterface, EquatableInterface, \JsonSerializable
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var \DateTime
     */
    protected $lastLoginAt;

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var bool
     */
    protected $status;

    /**
     * @var bool
     */
    protected $isAdmin;

    /**
     * @param array $data
     * @param bool $isAdmin
     */
    public function __construct(array $data, $isAdmin)
    {
        $this->setId($data['id'])
            ->setUsername($data['login'])
            ->setFirstName($data['firstname'])
            ->setLastName($data['lastname'])
            ->setEmail($data['mail'])
            ->setCreatedAt(new \DateTime($data['created_on']))
            ->setLastLoginAt(new \DateTime($data['last_login_on']))
            ->setApiKey($data['api_key']);
        if (array_key_exists('status', $data)) {
            $this->setStatus($data['status']);
        }
        $this->setIsAdmin($isAdmin);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return RedmineUser
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return RedmineUser
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return RedmineUser
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return RedmineUser
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return RedmineUser
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     * @return RedmineUser
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastLoginAt()
    {
        return $this->lastLoginAt;
    }

    /**
     * @param \DateTime $lastLoginAt
     * @return RedmineUser
     */
    public function setLastLoginAt($lastLoginAt)
    {
        $this->lastLoginAt = $lastLoginAt;
        return $this;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     * @return RedmineUser
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param boolean $status
     * @return RedmineUser
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

    /**
     * @param boolean $isAdmin
     * @return RedmineUser
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;
        return $this;
    }

    public function getRoles()
    {
        $roles = ['ROLE_REDMINE'];
        if ($this->getIsAdmin()) {
            $roles[] = 'ROLE_REDMINE_ADMIN';
        }
        return $roles;
    }

    public function getPassword()
    {
        return null;
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
        return null;
    }

    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof RedmineUser) {
            return false;
        }

        return $user->getId() == $this->getId();
    }

    function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'username' => $this->getUsername(),
            'firstName' => $this->getFirstName(),
            'lastName' => $this->getLastName(),
            'email' => $this->getEmail(),
            'createdAt' => $this->getCreatedAt()->format('c'),
            'lastLoginAt' => $this->getLastLoginAt()->format('c'),
            'apiKey' => $this->getApiKey(),
            'status' => $this->getStatus(),
            'admin' => $this->getIsAdmin()
        ];
    }
}
