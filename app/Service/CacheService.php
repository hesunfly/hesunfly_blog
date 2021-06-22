<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\Ad;
use App\Model\Config;
use App\Model\Page;
use Hyperf\Cache\Cache;

class CacheService extends Service
{
    protected $cache;

    public function __construct()
    {
        $this->cache = make(Cache::class);
    }

    public function getPages()
    {
        $pages = $this->cache->get('index_pages_items');

        if ($pages) {
            return unserialize($pages);
        }

        $pages = Page::query()
            ->where('status', 1)
            ->select(['slug', 'title'])
            ->orderBy('sort')
            ->get();

        $this->cache->set('index_pages_items', serialize($pages));

        return $pages;
    }

    public function deletePagesCache()
    {
        $this->cache->delete('index_pages_items');
    }

    public function getConfig($key)
    {
        $cache_key = 'config_' . $key;
        $config = $this->cache->get($cache_key);

        if ($config) {
            return $config;
        }

        $config = Config::$setting_title[$key]['default'];
        $db_config = Config::value($key);
        $config = $db_config ?: $config;

        $this->cache->set($cache_key, $config);

        return $config;
    }

    public function deleteConfig()
    {
        foreach (array_keys(Config::$setting_title) as $item) {
            $this->cache->delete('config_' . $item);
        }
    }

    public function getAds()
    {
        $ads = $this->cache->get('index_ads');

        if ($ads) {
            return unserialize($ads);
        }

        $ads = Ad::query()
            ->where('status', 1)
            ->select(['desc', 'url', 'image_path'])
            ->orderBy('sort')->get();

        $this->cache->set('index_ads', serialize($ads));

        return $ads;
    }

    public function deleteAds()
    {
        $this->cache->delete('index_ads');
    }
}
