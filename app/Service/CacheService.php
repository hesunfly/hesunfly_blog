<?php

declare(strict_types=1);

namespace App\Service;

//use App\Models\Ad;
use App\Model\Page;
use Hyperf\Cache\Cache;
use Hyperf\Di\Annotation\Inject;

//use App\Models\Setting;

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

    /*public static function getConfig($key)
    {
        $cache_key = 'config_' . $key;
        $config = Cache::get($cache_key);

        if (empty($config)) {
            $db_config = Setting::select($key)->first();
            $config = Setting::$setting_title[$key]['default'];
            if (!empty($db_config->$key)) {
                $config = $db_config->$key;
            }
        }

        Cache::forever($cache_key, $config);

        return $config;
    }

    public static function destroyConfig()
    {
        foreach (array_keys(Setting::$setting_title) as $item) {
            Cache::forget('config_' . $item);
        }
    }

    public static $avatar_key = 'config_avatar';

    public static function setAvatar($value)
    {
        Cache::forever(self::$avatar_key, $value);
    }

    public static function getAvatar()
    {
        $config = Cache::get(self::$avatar_key);
        if (empty($config)) {
            $config = '/assets/images/avatar.jpg';
        }

        return $config;
    }

    public static function getAds()
    {
        $ads = Cache::get('index_ads');

        if (empty($ads)) {
            $ads = Ad::whereRaw('status = 1')->select(['desc', 'url', 'img_path'])->orderBy('sort')->get();
        }

        Cache::forever('index_ads', $ads);

        return $ads;
    }

    public static function deleteAds()
    {
        Cache::forget('index_ads');
    }*/
}
