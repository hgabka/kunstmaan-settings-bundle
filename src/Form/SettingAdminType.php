<?php

namespace Hgabka\KunstmaanSettingsBundle\Form;

use Hgabka\KunstmaanExtensionBundle\Form\Type\StaticControlType;
use Hgabka\KunstmaanSettingsBundle\Choices\SettingTypes;
use Hgabka\KunstmaanSettingsBundle\Entity\Setting;
use Hgabka\KunstmaanSettingsBundle\Helper\SettingsManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class SettingAdminType extends AbstractType
{
    /** @var AuthorizationChecker */
    private $authChecker;

    /** @var SettingsManager */
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
        $builder->add('name', $this->authChecker->isGranted('ROLE_SUPER_ADMIN') ? TextType::class : StaticControlType::class, ['label' => 'hgabka_kuma_settings.labels.name']);
        if ($this->authChecker->isGranted('ROLE_SUPER_ADMIN')) {
            $builder->add('type', ChoiceType::class, ['label' => 'hgabka_kuma_settings.labels.type', 'choices' => iterator_to_array(new SettingTypes())]);
        }
        $builder->add('description', TextareaType::class, ['label' => 'hgabka_kuma_settings.labels.description']);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $setting = $event->getData();
            $form = $event->getForm();

            $data = $this->getFieldData($setting);

            $form->add('value', $data['type'], $data['options']);
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $this->getFieldData($form->getData(), $event->getData()['type']);

            $form->add('value', $data['type'], $data['options']);
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => function (FormInterface $form) {
                $setting = $form->getData();

                return [
                    'Default',
                    'Type'.ucfirst($setting->getType()),
                    Container::camelize($setting->getName()),
                ];
            },
        ]);
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

    protected function getFieldData(Setting $setting, $type = null)
    {
        $options = ['label' => 'hgabka_kuma_settings.labels.value'];
        $type = $type ?? $setting->getType();

        switch ($type) {
            case SettingTypes::INT:
                $fieldType = IntegerType::class;

                break;
            case SettingTypes::BOOL:
                $fieldType = ChoiceType::class;

                $options = [
                    'choices' => [
                        'hgabka_kuma_settings.labels.no' => 0,
                        'hgabka_kuma_settings.labels.yes' => 1,
                    ],
                    'data' => (int) $setting->getValue(),
                    'label' => 'hgabka_kuma_settings.labels.value',
                ];

                break;
            case SettingTypes::EMAIL:
                $fieldType = EmailType::class;

                break;
            case SettingTypes::FLOAT:
                $fieldType = NumberType::class;

                break;
            default:
                $fieldType = TextType::class;
        }

        return [
            'type' => $fieldType,
            'options' => $options,
        ];
    }
}
