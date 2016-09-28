<?php

namespace HubtrafficApi;

/**
 * Parse data from fantasti.cc api
 * @author Pavel Plzák <pavelplzak@protonmail.com>
 * @licence MIT
 * @version 1.0.0
 * @package HubtrafficApi
 */
class FantastiDataParser implements IDataParser {

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
			$video->addThumb($thumb->thumb);
		}
		foreach ($data->video->tags as $tag) {
			$video->addTag($tag->tag);
		}

		return $video;
	}

	/**
	 * @inheritdoc
	 */
	public function parseEmbedData($data) {
		return htmlspecialchars_decode($data->embed->code);
	}


}