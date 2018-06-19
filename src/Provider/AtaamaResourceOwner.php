<?php

namespace Ataama\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Tool\ArrayAccessorTrait;

class AtaamaResourceOwner implements ResourceOwnerInterface
{
    use ArrayAccessorTrait;

    /**
     * Raw response
     *
     * @var array
     */
    protected $response;

    /**
     * @param array $response
     */
    public function __construct(array $response)
    {
        $this->response = $response;
    }

    /**
     * Returns the uuid of the authorized resource owner
     *
     * @return string
     */
    public function getId()
    {
        return $this->getValueByKey($this->response, 'user_id');
    }

    /**
     * Returns the username
     *
     * @return string
     */
    public function getName()
    {
        return $this->getValueByKey($this->response, 'username');
    }

    /**
     * Returns the URL for the user's icon
     * Always ends with "?n" where n is a cache-busting number incremented after each avatar change
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->getValueByKey($this->response, 'usericon');
    }

    /**
     * Returns the user's "type"
     * Known values are:
     *  - deactivated (used for deleted/banned accounts too)
     *  - lead
     *  - student
     *  - affiliate
     *  - coach
     *  - supercoach
     *  - admin
     *
     * @return string
     */
    public function getType()
    {
        return $this->getValueByKey($this->response, 'type');
    }

    public function toArray()
    {
        return $this->response;
    }
}
