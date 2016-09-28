<?php

namespace HubtrafficApi;


/**
 * Parse data from spankwire api
 * @author Pavel Plzák <pavelplzak@protonmail.com>
 * @licence MIT
 * @version 1.0.0
 * @package HubtrafficApi
 */
class SpankwireDataParser implements IDataParser {

	/**
	 * @inheritdoc
	 */
	public function parseVideoData($source, $videoId, $data) {
		$video = new Video($source, $videoId);

		$video->setUrl($data->video->url);
		$video->setRating((double)$data->video->rating);
		$video->setRatingCount((int)$data->video->ratings);
		$video->setPublishDate(new \DateTime($data->video->publish_date));

		$video->setTitle($data->video->title);
		$video->setDuration($data->video->duration);

		foreach ($data->video->thumbs as $thumb) {
			$video->addThumb($thumb->src);
		}

		$video->setTags((array)$data->video->tags);

		return $video;
	}

	/**
	 * @inheritdoc
	 */
	public function parseEmbedData($data) {
		return base64_decode($data->embed->code);
	}


}