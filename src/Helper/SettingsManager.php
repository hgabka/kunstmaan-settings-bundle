<?php

namespace Hgabka\KunstmaanSettingsBundle\Helper;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\PropertyAccess\Exception\InvalidArgumentException;

class SettingsManager
{
    /**
     * @var Registry
     */
    protected $doctrine;

    /**
     * SettingsManager constructor.
     *
     * @param Registry $doctrine
     */
    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }
}