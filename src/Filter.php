<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/19
 * Time: 16:40
 */

namespace Waiterphp\Core;

use Waiterphp\Core\FilterTrait as FilterTrait;

class Filter
{
    use FilterTrait;

    public static function from($data)
    {
        return new self($data);
    }

    private function __construct($data)
    {
        $this->setData($data);
    }
}