<?php
/**
 * Created by PhpStorm.
 * User: sfhun
 * Date: 2017.09.02.
 * Time: 16:45
 */

namespace Hgabka\KunstmaanSettingsBundle\Form;

use Hgabka\KunstmaanSettingsBundle\Choices\SettingTypes;
use Hgabka\KunstmaanSettingsBundle\Helper\SettingsManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Hgabka\KunstmaanExtensionBundle\Form\Type\StaticControlType;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class SettingAdminType extends AbstractType
{
    /** @var AuthorizationChecker  */
    private $authChecker;

    /** @var  SettingsManager */
    private $settingsManager;

    public function __construct(AuthorizationChecker $authChecker, SettingsManager $settingsManager)
    {
        $this->authChecker = $authChecker;
        $this->settingsManager = $settingsManager;
    }

    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting form the
     * top most type. Type extensions can further modify the form.
     *
     * @see FormTypeExtensionInterface::buildForm()
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', $this->authChecker->isGranted('ROLE_SUPER_ADMIN') ? TextType::class : StaticControlType::class, ['label' => 'Név']);
        if ($this->authChecker->isGranted('ROLE_SUPER_ADMIN')) {
            $builder->add('type', ChoiceType::class, ['label' => 'Típus', 'choices' => iterator_to_array(new SettingTypes())]);
        }
        $builder->add('value', null, ['label' => 'Érték']);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getBlockPrefix()
    {
        return 'hgabka_kunstmaansettings_setting_type';
    }
}