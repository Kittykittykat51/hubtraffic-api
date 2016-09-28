<?php

namespace HubtrafficApi;

/**
 * DataParser interface. There are implementations of this interface for every server.
 * @author Pavel Plzák <pavelplzak@protonmail.com>
 * @licence MIT
 * @version 1.0.0
 * @package HubtrafficApi
 */
interface IDataParser {

	/**
	 * @param string $source
	 * @param string $videoId
	 * @param \stdClass $data
	 * @return Video
	 */
	public function parseVideoData($source, $videoId, $data);

	/**
	 * @param \stdClass $data
	 * @return string
	 */
	public function parseEmbedData($data);


}