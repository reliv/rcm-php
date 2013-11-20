<?php
namespace Rcm\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="rcm_key_value")
 */
class KeyValue
{
    /**
     * @var string keyName
     *
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    protected $keyName;

    /**
     * @var int Owners account number
     *
     * @ORM\Column(type="text")
     */
    protected $value;

    /**
     * @param string $keyName
     */
    public function setKeyName($keyName)
    {
        $this->keyName = $keyName;
    }

    /**
     * @return string
     */
    public function getKeyName()
    {
        return $this->keyName;
    }

    /**
     * @param int $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }
}