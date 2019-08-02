<?php

namespace RcmUser\Acl\Entity;

use RcmUser\Acl\Filter\ResourceIdFilter;
use RcmUser\Acl\Validator\ResourceIdValidator;
use RcmUser\Exception\RcmUserException;
use Zend\Permissions\Acl\Resource\GenericResource;

/**
 * Class AclResource
 *
 * AclResource
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmUser\Acl\Entity
 * @author    James Jervis <jjervis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class AclResource extends GenericResource implements \JsonSerializable
{
    /**
     * @var string $providerId The resource provider Id
     */
    protected $providerId = null;

    /**
     * @var string $parentResourceId
     */
    protected $parentResourceId = null;

    /**
     * @var AclResource $parentResource
     */
    protected $parentResource = null;

    /**
     * @var array $privileges
     */
    protected $privileges = [];

    /**
     * @var string $name
     */
    protected $name = '';

    /**
     * @var string $description
     */
    protected $description = '';

    /**
     * __construct
     *
     * @param string $resourceId       resourceId
     * @param null   $parentResourceId parentResourceId
     * @param array  $privileges       privileges
     */
    public function __construct(
        $resourceId,
        $parentResourceId = null,
        $privileges = []
    ) {
        $this->setResourceId($resourceId);
        $this->setParentResourceId($parentResourceId);
        $this->setPrivileges($privileges);
    }

    /**
     * setResourceId
     *
     * @param string $resourceId resourceId
     *
     * @return void
     * @throws \RcmUser\Exception\RcmUserException
     */
    public function setResourceId($resourceId)
    {
        $resourceId = ResourceIdFilter::filter($resourceId);

        if (!$this->isValidResourceId($resourceId) || empty($resourceId)) {
            throw new RcmUserException(
                "Resource resourceId ({$resourceId}) is invalid."
            );
        }

        $this->resourceId = $resourceId;
    }

    /**
     * getResourceId
     *
     * @return string
     */
    public function getResourceId()
    {
        return $this->resourceId;
    }

    /**
     * @deprecated Not required
     * setProviderId
     *
     * @param string $providerId providerId
     *
     * @return void
     */
    public function setProviderId($providerId)
    {
        $this->providerId = $providerId;
    }

    /**
     * @deprecated Not required
     * getProviderId
     *
     * @return string
     */
    public function getProviderId()
    {
        return $this->providerId;
    }

    /**
     * setParentResourceId
     *
     * @param string|null $parentResourceId parentResourceId
     *
     * @return void
     * @throws \RcmUser\Exception\RcmUserException
     */
    public function setParentResourceId($parentResourceId)
    {
        $parentResourceId = ResourceIdFilter::filter($parentResourceId);

        if (!$this->isValidResourceId($parentResourceId)) {
            throw new RcmUserException(
                "Resource parentResourceId ({$parentResourceId}) is invalid."
            );
        }

        if (!empty($this->parentResource)) {
            if ($this->parentResource->getResourceId() !== $parentResourceId) {
                $this->parentResource = null;
            }
        }

        if (empty($parentResourceId)) {
            $parentResourceId = null;
        }

        $this->parentResourceId = $parentResourceId;
    }

    /**
     * getParentResourceId
     *
     * @return string|null
     */
    public function getParentResourceId()
    {
        return $this->parentResourceId;
    }

    /**
     * @deprecated Should only require parentResourceId
     * setParentResource
     *
     * @param AclResource $parentResource parentResource
     *
     * @return void
     */
    public function setParentResource(AclResource $parentResource)
    {
        $this->setParentResourceId($parentResource->getResourceId());
        $this->parentResource = $parentResource;
    }

    /**
     * @deprecated Should only return parentResourceId
     * getParentResource
     *
     * @return string|AclResource
     */
    public function getParentResource()
    {
        if (empty($this->parentResource)) {
            return $this->getParentResourceId();
        }

        return $this->parentResource;
    }

    /**
     * setPrivileges
     *
     * @param array $privileges privileges
     *
     * @return void
     */
    public function setPrivileges($privileges)
    {
        $this->privileges = $privileges;
    }

    /**
     * getPrivileges
     *
     * @return array
     */
    public function getPrivileges()
    {
        return $this->privileges;
    }

    /**
     * setName
     *
     * @param string $name name
     *
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * getName
     *
     * @return string
     */
    public function getName()
    {
        if (empty($this->name)) {
            return $this->getResourceId();
        }

        return $this->name;
    }

    /**
     * setDescription
     *
     * @param string $description description
     *
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * getDescription
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * isValidResourceId
     *
     * @param string $resourceId resourceId
     *
     * @return bool
     */
    public function isValidResourceId($resourceId)
    {
        return ResourceIdValidator::isValid($resourceId);
    }

    /**
     * populate
     *
     * @param array $data data
     *
     * @return void
     * @throws \RcmUser\Exception\RcmUserException
     */
    public function populate($data = [])
    {
        if (($data instanceof AclResource)) {
            $this->setResourceId($data->getResourceId());
            $this->setProviderId($data->getProviderId());
            $this->setParentResourceId($data->getParentResourceId());
            $this->setPrivileges($data->getPrivileges());
            $this->setName($data->getName());
            $this->setDescription($data->getDescription());

            return;
        }

        if (is_array($data)) {
            if (isset($data['resourceId'])) {
                $this->setResourceId($data['resourceId']);
            }
            if (isset($data['providerId'])) {
                $this->setProviderId($data['providerId']);
            }
            if (isset($data['parentResourceId'])) {
                $this->setParentResourceId($data['parentResourceId']);
            }
            if (isset($data['privileges'])) {
                $this->setPrivileges($data['privileges']);
            }
            if (isset($data['name'])) {
                $this->setName($data['name']);
            }
            if (isset($data['description'])) {
                $this->setDescription($data['description']);
            }

            return;
        }

        throw new RcmUserException(
            'Resource data could not be populated, data format not supported'
        );
    }

    /**
     * toArray
     *
     * @param array $ignore
     *
     * @return array
     */
    public function toArray($ignore = [])
    {
        $data = [];

        if (!in_array('resourceId', $ignore)) {
            $data['resourceId'] = $this->getResourceId();
        }
        if (!in_array('providerId', $ignore)) {
            $data['providerId'] = $this->getProviderId();
        }
        if (!in_array('parentResourceId', $ignore)) {
            $data['parentResourceId'] = $this->getParentResourceId();
        }
        if (!in_array('privileges', $ignore)) {
            $data['privileges'] = $this->getPrivileges();
        }
        if (!in_array('name', $ignore)) {
            $data['name'] = $this->getName();
        }
        if (!in_array('description', $ignore)) {
            $data['description'] = $this->getDescription();
        }

        return $data;
    }

    /**
     * jsonSerialize
     *
     * @return \stdClass
     */
    public function jsonSerialize()
    {
        $obj = new \stdClass();
        $obj->resourceId = $this->getResourceId();
        $obj->providerId = $this->getProviderId();
        $obj->parentResourceId = $this->getParentResourceId();
        $obj->privileges = $this->getPrivileges();
        $obj->name = $this->getName();
        $obj->description = $this->getDescription();

        return $obj;
    }
}
