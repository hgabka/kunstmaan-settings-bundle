<?php
/**
 * Created by PhpStorm.
 * User: sfhun
 * Date: 2017.09.02.
 * Time: 21:19
 */

namespace Hgabka\KunstmaanSettingsBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Hgabka\KunstmaanSettingsBundle\Entity\Setting;
use Hgabka\KunstmaanSettingsBundle\Helper\SettingsManager;

class SettingSubscriber implements EventSubscriber
{
    /** @var SettingsManager $settingsManager */
    private $settingsManager;

    /**
     * SettingListener constructor.
     * @param SettingsManager $settingsManager
     */
    public function __construct(SettingsManager $settingsManager)
    {
        $this->settingsManager = $settingsManager;
    }

    public function getSubscribedEvents()
    {
        return array(
            'postPersist',
            'postUpdate',
            'preRemove'
        );
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $object = $args->getObject();

        if (!$object instanceof Setting) {
            return;
        }

        $this->settingsManager->addToCache($object->getName(), $object->getValue());
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $object = $args->getObject();

        if (!$object instanceof Setting) {
            return;
        }

        $this->settingsManager->removeFromCache($object->getName());
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $object = $args->getObject();

        if (!$object instanceof Setting) {
            return;
        }

        $this->settingsManager->addToCache($object->getName(), $object->getValue());
    }
}
