<?php
namespace App\Models\Services;

use Cache;
use DB;

/**
 * This is class of cache
 * @author TienDQ
 * When using need : use App\Models\Services\CustomCache;
 */
class Caching
{
    private $_storage = null;
    protected static $_instance = null;

    public function __construct($storage = null)
    {
        if (empty($storage)) {
            $storage = config('cms.cache.storage');
        }

        $this->_storage = Cache::store($storage);
    }

    final public static function getInstance($storage = null)
    {
        //Check instance
        if (is_null(self::$_instance)) {
            self::$_instance = new self($storage);
        }

        //Return instance
        return self::$_instance;
    }

    /**
     * Retrieving Items from the cache
     * @param string $key
     * @param array $tags
     * @return array or objects
     */
    public function getCache($key, $tags = null)
    {
        if (empty($tags)) {
            return $this->_storage->get($key);
        }

        return $this->_storage->tags((array) $tags)->get($key);
    }

    /**
     * To retrieve all data from the cache.If they don't exist,
     * retrieve them from the database and add them to the cache
     * @param type $key
     * @param type $minutes
     * @param type $sql
     * @return values
     */
    public function getCacheByQuery($key, $minutes, $sql)
    {
        $datas = $this->_storage->remember($key, $minutes, function () use ($sql) {
            return DB::select($sql);
        });

        return $datas;
    }

    /**
     * Retrieve an item from the cache and then delete it
     * @param type $key
     * @return item, NULL if item does not exist in the cache
     */
    public function getCacheAndDelete($key)
    {
        return $this->_storage->pull($key);
    }

    /**
     * Storing Items In The Cache
     * @param string $key
     * @param mixed $value
     * @param int $minutes
     */
    public function writeCache($key, $value, $times = null)
    {
        $result = false;

        if (!isset($times)) {
            $times = config('cms.cache.time_expired');
        }

        if (isset($key) && isset($value) && isset($times)) {
            $this->_storage->put($key, $value, $times);
            $result = true;
        }

        return $result;
    }

    /**
     * Storing Items in the cache permanently
     * @param type $key
     * @param type $value
     * @return boolean
     */
    public function writeCacheForever($key, $value)
    {
        $result = false;

        if (isset($key) && isset($value)) {
            $this->_storage->forever($key, $value);
            $result = true;
        }

        return $result;
    }

    /**
     * Storing Items In The Cache with tags
     * @param string $key
     * @param mixed $value
     * @param mixed $tags
     * @param int $minutes
     */
    public function writeCacheTags($key, $value, $tags, $times = null)
    {
        $result = false;

        if (!isset($times)) {
            $times = config('cms.cache.time_expired');
        }
        if (isset($key) && isset($value) && isset($times)) {
            $this->_storage->tags((array) $tags)->put($key, $value, $times);
            $result = true;
        }

        return $result;
    }

    /**
     * Checking For Item Existence
     * @param type $key
     * @return boolean
     */
    public function existKey($key = null)
    {
        $result = false;

        if ($this->_storage->has($key)) {
            $result = true;
        }

        return $result;
    }

    /**
     * Removing Items From The Cache
     * @param type $key
     * @return boolean
     */
    public function deleteCache($key)
    {
        return $this->_storage->forget($key);
    }

    /**
     * Clear the entire cache
     * @return boolean
     */
    public function deleteAllCache()
    {
        return $this->_storage->flush();
    }
}
