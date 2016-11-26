<?php
/**
 * This file is part of the Slave package.
 *
 * Copyright (c) 2016. Aymen FNAYOU <fnayou.aymen@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slave\Utils;

/**
 * Class Bag.
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
     * @param string $parameter
     *
     * @return bool
     */
    public function hasParameter($parameter)
    {
        return $this->has($parameter);
    }

    /**
     * @param string $parameter
     *
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
     * @param string $parameter
     * @param mixed  $value
     */
    public function setParameter($parameter, $value)
    {
        $this->set($parameter, $value);
    }

    /**
     * @param string $parameter
     * @param array  $value
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
