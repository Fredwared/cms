<?php
namespace App\Models\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Goutte\Client;
use PHPushbullet\PHPushbullet;
use Symfony\Component\DomCrawler\Crawler;

/**
 * This is class write methods common
 * @author TienDQ
 * When using need : use App\Models\Services\Globals;
 */
class Globals
{
    /**
     * @param $key
     * @return $key
     */
    public static function makeCacheKey($key, $args = null)
    {
        if (empty($args)) {
            return $key;
        }

        return vsprintf($key, $args);
    }

    public static function getCacheDriver($storage)
    {
        $instance = Cache::store($storage);

        return $instance;
    }

    public static function getBusiness($businessName)
    {
        $businessName = 'Business_' . $businessName;

        $adapterName = '\App\Models\Entity\Business\\' . ucfirst($businessName);
        $instance = new $adapterName();

        return $instance;
    }

    public static function getModel($modelName)
    {
        $modelName = 'Model_' . $modelName;

        $adapterName = '\App\Models\Entity\Model\\' . ucfirst($modelName);
        $instance = new $adapterName();

        return $instance;
    }
    
    /**
     * Sends an email
     *
     * @param string $template Template name
     * @param array $data Email data. Must contain
     * @example
     * [
     *  'mail_to' => <recipient email address>,
     *  'recipient_name' => <recipient_name>,
     *  'mail_from' => <sender_address>,
     *  'sender_name' => <sender_name>,
     *  'subject' => <email_subject>
     * ]
     * @return bool
     */
    
    public static function sendMail($template, array $data)
    {
        Mail::send($template, $data, function ($msg) use ($data) {
            $msg->to($data['mail_to'], $data['recipient_name']);
            $msg->from($data['mail_from'], $data['sender_name']);
            $msg->replyTo($data['mail_from'], $data['sender_name']);
            $msg->subject($data['subject']);
        });
    
        // Check for email failures
        if (count(Mail::failures()) > 0) {
            return false;
        }

        return true;
    }

    /**
     * get content cross domain
     * @param <string> $url
     * @return <string>
     * @author TienDQ
     */
    public static function getContent($url, $method = 'get', $params = [])
    {
        $content = '';
        $url = trim($url);
        $method = strtolower($method);

        if (function_exists('curl_init')) {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);

            if ($method == 'post') {
                curl_setopt($ch, CURLOPT_POST, true);
                if (!empty($params)) {
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
                }
            }

            if (starts_with($url, 'https')) {
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            }

            $content = curl_exec($ch);
            curl_close($ch);
        } else {
            if (ini_get('allow_url_fopen') == '1') {
                @$data = file_get_contents($url);
                if ($data) {
                    $content = $data;
                }
            }
        }

        return $content;
    }

    /**
     * make crawler
     * @param <string> $url
     * @return <object> Symfony\Component\DomCrawler\Crawler
     * @author TienDQ
     */
    public static function makeRequest($url, $method = 'get', $params = [])
    {
        try {
            $client = new Client();
            $crawler = $client->request($method, trim($url), $params);

            $response = $client->getResponse();
            if ($response->getStatus() != 200) {
                $content = self::getContent($url);

                if ($content) {
                    $crawler = new Crawler(null, $url);
                    $crawler->addContent($content);

                    return ['client' => $client, 'crawler' => $crawler];
                }

                return null;
            }

            return ['client' => $client, 'crawler' => $crawler];
        } catch (\Exception $ex) {
            return null;
        }
    }

    public static function downloadFileCurl($url, $pathStorage, $newFile)
    {
        $fileTemp = $pathStorage . $newFile;
        $ch = curl_init(trim($url));
        $fp = fopen($fileTemp, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);

        if (file_exists($fileTemp)) {
            return $fileTemp;
        }

        return false;
    }

    public static function getPushBullet()
    {
        return new PHPushbullet('o.GvKMLyADQjingdV06lu8zyPV6anCd8SR');
    }
}
