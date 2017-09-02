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
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Hgabka\KunstmaanExtensionBundle\Form\Type\StaticControlType;

class SettingAdminType extends AbstractType
{
    /** @var TokenStorageInterface  */
    private $tokenStorage;

    /** @var  SettingsManager */
    private $settingsManager;

    public function __construct(TokenStorageInterface $tokenStorage, SettingsManager $settingsManager)
    {
        $this->tokenStorage = $tokenStorage;
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
        $user = $this->tokenStorage->getToken()->getUser();
        $builder->add('name', $user->isGranted('ROLE_SUPER_ADMIN') ? TextType::class : StaticControlType::class, ['label' => 'Név']);
        if ($user->isGranted('ROLE_SUPER_ADMIN')) {
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