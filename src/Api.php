<?php

namespace HubtrafficApi;

/**
 * Hubtraffic api wrapper
 * @author Pavel Plzák <pavelplzak@protonmail.com>
 * @licence MIT
 * @version 1.0.0
 * @package HubtrafficApi
 */
class Api {

	const SOURCE_REDTUBE = 'redtube';

	const SOURCE_PORNHUB = 'pornhub';

	const SOURCE_YOUPORN = 'youporn';

	const SOURCE_TUBE8 = 'tube8';

	const SOURCE_FANTASTICC = 'fantasti';

	const SOURCE_SPANKWIRE = 'spankwire';


	/** @var array */
	private $config = [
		self::SOURCE_REDTUBE => [
			'serverName' => 'RedTube.com',
			'pattern' => '/([0-9]+)',
			'url' => [
				'video' => 'http://api.redtube.com/?data=redtube.Videos.getVideoById&output=json&thumbsize=big&video_id=',
				'embed' => 'http://api.redtube.com/?data=redtube.Videos.getVideoEmbedCode&output=json&video_id=',
			]
		],
		self::SOURCE_PORNHUB => [
			'serverName' => 'PornHub.com',
			'pattern' => '/view_video.php\?viewkey=([a-z0-9]+)',
			'url' => [
				'video' => 'http://www.pornhub.com/webmasters/video_by_id?thumbsize=large_hd&id=',
				'embed' => 'http://www.pornhub.com/webmasters/video_embed_code?id=',
			]
		],
		self::SOURCE_YOUPORN => [
			'serverName' => 'YouPorn.com',
			'pattern' => '/watch/([0-9]+)/.*',
			'url' => [
				'video' => 'http://www.youporn.com/api/webmasters/video_by_id/?output=json&thumbsize=big&video_id=',
				'embed' => 'http://www.youporn.com/api/webmasters/video_embed_code/?video_id=',
			]
		],
		self::SOURCE_TUBE8 => [
			'serverName' => 'Tube8.com',
			'pattern' => '/.*/.*/([0-9]+)',
			'url' => [
				'video' => 'http://api.tube8.com/api.php?action=getvideobyid&output=json&thumbsize=big&video_id=',
				'embed' => 'http://api.tube8.com/api.php?action=getvideoembedcode&output=json&video_id=',
			]
		],
		self::SOURCE_FANTASTICC => [
			'serverName' => 'Fantasti.cc',
			'pattern' => '/.*/([0-9]+)',
			'url' => [
				'video' => 'http://fantasti.cc/wm/getVideoById.json?thumbsize=big&video_id=',
				'embed' => 'http://fantasti.cc/wm/getVideoEmbedCode.json?video_id=',
			]
		],
		self::SOURCE_SPANKWIRE => [
			'serverName' => 'SpankWire.com',
			'pattern' => '/.*/video([0-9]+)',
			'url' => [
				'video' => 'http://www.spankwire.com/api/HubTrafficApiCall?data=getVideoById&thumbsize=large&output=json&video_id=',
				'embed' => 'http://www.spankwire.com/api/HubTrafficApiCall?data=getVideoEmbedCode&output=json&video_id=',
			]
		],
	];

	/** @var array */
	private $proxies = [];


	/**
	 * Returns config for concrete source
	 * @param string $source
	 * @return array
	 */
	public function getConfig($source) {
		if (!array_key_exists($source, $this->config)) {
			throw new \InvalidArgumentException('Source not found');
		}

		return $this->config[$source];
	}

	public function setProxies(array $proxies) {
		$this->proxies = $proxies;
	}




	/**
	 * Returns video object
	 * @param string $url
	 * @return \HubtrafficApi\Video
	 */
	public function getVideo($url) {
		$details = $this->parseUrl($url);
		return $this->getVideoBySourceAndId($details['source'], $details['videoId']);
	}


	/**
	 * Returns video object by source and video id
	 * @param string $source
	 * @param string $id
	 * @return \HubtrafficApi\Video
	 */
	public function getVideoBySourceAndId($source, $id) {
		$config = $this->config[$source];

		$videoData = $this->getApiData($config['url']['video'] . $id);
		if (empty($videoData->video)) {
			return false;
		}

		$video = $this->getDataParser($source)->parseVideoData($source, $id, $videoData);

		$embedData = $this->getApiData($config['url']['embed'] . $id);
		if ($embedData) {
			$embed = $this->getDataParser($source)->parseEmbedData($embedData);
			if ($embed) {
				$parts = explode('</iframe>', $embed);
				$video->setEmbed($parts[0] . '</iframe>');
			}
		}

		return $video;
	}


	/**
	 * Parses video url and returns source and video id
	 * @param string $videoUrl
	 * @throws UnsupportedSourceException
	 * @return array
	 */
	private function parseUrl($videoUrl) {
		preg_match('~(?:www\.)?([a-z0-9]+)\.[a-z]+~', parse_url($videoUrl, PHP_URL_HOST), $sourceMatches);
		$source = $sourceMatches[1];

		if (!array_key_exists($source, $this->config)) {
			throw new UnsupportedSourceException('Unsupported source.');
		}

		preg_match('~'.$this->config[$source]['pattern'].'~', $videoUrl, $videoIdMatches);
		$videoId = $videoIdMatches[1];

		if (!empty($videoId)) {
			return [
				'source' => $source,
				'videoId' => $videoId,
			];
		}

		return false;
	}


	/**
	 * Sends request to api url and parse json result
	 * @param string $url
	 * @return stdClass
	 */
	private function getApiData($url) {
		$videoData = null;
		$c = curl_init($url);

		curl_setopt($c, CURLOPT_HEADER, FALSE);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);

		if ($this->proxies) {
			foreach ($this->proxies as $proxy) {
				curl_setopt($c, CURLOPT_PROXY, $proxy);
				curl_setopt($c, CURLOPT_TIMEOUT, 5);
				$result = curl_exec($c);
				if ($result) {
					$videoData = json_decode($result);
					if ($videoData) {
						break;
					}
				}
			}
		} else {
			$result = curl_exec($c);
			$videoData = json_decode($result);
		}

		curl_close($c);
		return $videoData;
	}


	/**
	 * Returns instance of data parser
	 * @param string $source
	 * @throws \Exception
	 * @return IDataParser
	 */
	private function getDataParser($source) {
		$parserClassName = '\\' . __NAMESPACE__ . '\\' . ucfirst($source) . 'DataParser';
		if (class_exists($parserClassName)) {
			return new $parserClassName;
		} else {
			throw new \Exception('Data parser class not found');
		}
	}


}


class UnsupportedSourceException extends \Exception {};