<?php

/*
 * This file is part of PHP CS Fixer.
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Hgabka\KunstmaanSettingsBundle\Helper;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Cache\Simple\FilesystemCache;

class SettingsManager
{
    const CACHE_KEY = 'systemsettings';

    /**
     * @var Registry
     */
    protected $doctrine;

    /**
     * @var string
     */
    protected $cacheDir;

    /**
     * @var array
     */
    protected $settings;

    /**
     * @var FilesystemCache
     */
    protected $cache;

    /**
     * SettingsManager constructor.
     *
     * @param Registry $doctrine
     * @param $cacheDir
     */
    public function __construct(Registry $doctrine, $cacheDir)
    {
        $this->doctrine = $doctrine;
        $this->cacheDir = $cacheDir;
    }

    /**
     * Az összes beállítás lekérdezése a cache-ből.
     *
     * @return array
     */
    public function getCacheData()
    {
        $cache = $this->getCache();
        if (!$cache->has(self::CACHE_KEY)) {
            return $this->regenerateCache();
        }

        return $cache->get(self::CACHE_KEY, []);
    }

    /**
     * Kulcs-érték pár hozzáadása a cache-hez.
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return bool
     */
    public function addToCache($name, $value)
    {
        $cache = $this->getCache();

        $data = $cache->get(self::CACHE_KEY, []);
        $data[$name] = $value;

        return $cache->set(self::CACHE_KEY, $data);
    }

    /**
     * Beállítás törlése a cache-ből.
     *
     * @param string $name
     *
     * @return bool
     */
    public function removeFromCache($name)
    {
        $cache = $this->getCache();

        $data = $cache->get(self::CACHE_KEY, []);
        if (array_key_exists($name, $data)) {
            unset($data[$name]);

            return $cache->set(self::CACHE_KEY, $data);
        }

        return true;
    }

    public function regenerateCache()
    {
        $cache = $this->getCache();
        $cache->clear();
        $data = [];

        foreach ($this->doctrine->getRepository('HgabkaKunstmaanSettingsBundle:Setting')->findAll() as $setting) {
            $data[$setting->getName()] = $setting->getValue();
        }

        $cache->set(self::CACHE_KEY, $data);

        return $data;
    }

    public function get($name, $defaultValue = null)
    {
        if (empty($this->settings)) {
            $this->settings = $this->getCacheData();
        }

        return array_key_exists($name, $this->settings) ? $this->settings[$name] : $defaultValue;
    }

    protected function getCache()
    {
        if (null === $this->cache) {
            $this->cache = new FilesystemCache(self::CACHE_KEY, 0, $this->cacheDir.DIRECTORY_SEPARATOR.'systemsetting');
        }

        return $this->cache;
    }
}
