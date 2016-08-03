<?php
namespace Slave\Utils;

/**
 * Class Bag
 * @package Slave\Utils
 */
/**
 * Class Bag
 * @package Slave\Utils
 */
class Bag extends \Slave\Utils\DotNotation
{
    /**
     * Bag constructor.
     */
    public function __construct()
    {
        parent::__construct([]);
    }

    /**
     * @param $parameter
     * @return bool
     */
    public function hasParameter($parameter)
    {
        return $this->has($parameter);
    }

    /**
     * @param $parameter
     * @return mixed
     */
    public function getParameter($parameter)
    {
        if (!$this->hasParameter($parameter)) {
            throw new \InvalidArgumentException(sprintf(
                'Cannot find parameter "%s"',
                $parameter
            ));
        }

        return $this->get($parameter);
    }

    /**
     * @param $parameter
     * @param $value
     */
    public function setParameter($parameter, $value)
    {
        $this->set($parameter, $value);
    }

    /**
     * @param $parameter
     * @param array $value
     */
    public function addParameter($parameter, array $value)
    {
        $this->add($parameter, $value);
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->flattenValues($this->getValues());
    }

    /**
     * @return array
     */
    public function getRawParameters()
    {
        return $this->getValues();
    }

    /**
     * @param array $parameters
     */
    public function setParameters(array $parameters)
    {
        $this->setValues($parameters);
    }
}
