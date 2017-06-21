<?php
/**
 * This file is part of the fnayou/slave package.
 *
 * Copyright (c) 2016. Aymen FNAYOU <fnayou.aymen@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fnayou\Slave;

use Fnayou\Dotted;

/**
 * Class Bag.
 */
class Bag extends Dotted
{
    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->getValues();
    }

    /**
     * @return array
     */
    public function getFlattenParameters()
    {
        return $this->flatten();
    }

    /**
     * @param array $parameters
     */
    public function setParameters(array $parameters)
    {
        $this->setValues($parameters);
    }
}
