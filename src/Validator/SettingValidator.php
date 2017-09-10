<?php

/*
 * This file is part of PHP CS Fixer.
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Hgabka\KunstmaanSettingsBundle\Validator;

use Hgabka\KunstmaanSettingsBundle\Helper\SettingsManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class SettingValidator extends ConstraintValidator
{
    /**
     * @var SettingsManager
     */
    private $manager;

    public function __construct(SettingsManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param mixed              $value
     * @param Constraint|Setting $constraint
     *
     * @throws \Exception
     */
    public function validate($value, Constraint $constraint)
    {
        $val = $this->manager->get($constraint->name);

        switch (strtoupper($constraint->comparator)) {
            case '=':
            case 'EQUALS':
                $check = $value === $val;

                break;
            case '>':
            case 'GREATER':
                $check = $value > $val;

                break;
            case '<':
            case 'LESSER':
                $check = $value < $val;

                break;
            default:
                throw new \Exception('Invalid comparator');
        }

        if (!$check) {
            $this->context
                ->buildViolation('asd')
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->addViolation()
            ;
        }
    }
}
