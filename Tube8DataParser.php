<?php

namespace HubtrafficApi;


/**
 * Parse data from tube8 api
 * @author Pavel Plzák <pavelplzak@protonmail.com>
 * @license MIT
 * @version 1.0.0
 * @package HubtrafficApi
 */
class Tube8DataParser implements IDataParser {

	/**
	 * @inheritdoc
	 */
	public function parseVideoData($source, $videoId, $data) {
		$video = new Video($source, $videoId);

		$video->setUrl($data->video->url);
		$video->setRating((double)$data->video->rating);
		$video->setRatingCount((int)$data->video->ratings);
		$video->setPublishDate(new \DateTime($data->video->publish_date));

		$video->setTitle($data->title);

		$duration = (int)$data->video->duration;
		$video->setDuration((floor($duration / 60)) . ':' . ($duration % 60));

		foreach (reset($data->thumbs) as $thumb) {
			$video->addThumb($thumb);
		}
		foreach ($data->tags as $tag) {
			$video->addTag($tag);
		}

		return $video;
	}

	/**
	 * @inheritdoc
	 */
	public function parseEmbedData($data) {
		return base64_decode($data);
	}


}