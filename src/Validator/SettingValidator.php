<?php
/**
 * Created by PhpStorm.
 * User: sfhun
 * Date: 2017.09.02.
 * Time: 8:00
 */

namespace Hgabka\KunstmaanSettingsBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Hgabka\KunstmaanSettingsBundle\Helper\SettingsManager;

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
     * @param mixed $value
     * @param Setting|Constraint $constraint
     * @throws \Exception
     */
    public function validate($value, Constraint $constraint)
    {
        $val = $this->manager->get($constraint->name);

        switch (strtoupper($constraint->comparator)) {
            case '=':
            case 'EQUALS':
                $check = $value == $val;
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