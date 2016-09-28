<?php

namespace HubtrafficApi;

/**
 * Video data object
 * @package HubtrafficApi
 * @author Pavel Plzák <pavelplzak@protonmail.com>
 * @license MIT
 * @version 1.0.0
 * 
 * @property-read string $id
 * @property-read string $source
 * @property string $title
 * @property string $url
 * @property string $duration
 * @property double $rating
 * @property int $ratingCount
 * @property \DateTime $publishDate
 * @property string $embed
 * @property array $thumbs
 * @property array $tags
 * @property array $pornstars
 */
class Video {

	/** @var string */
	private $id;

	/** @var string */
	private $source;

	/** @var string */
	private $title;

	/** @var string */
	private $url;

	/** @var string */
	private $duration;

	/** @var double */
	private $rating;

	/** @var int */
	private $ratingCount;

	/** @var \DateTime */
	private $publishDate;

	/** @var string */
	private $embed;

	/** @var array */
	private $thumbs = array();

	/** @var array */
	private $tags = array();

	/** @var array */
	private $pornstars = array();


	/**
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}


	/**
	 * @return string
	 */
	public function getSource() {
		return $this->source;
	}


	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}


	/**
	 * @return string
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * @param string $url
	 */
	public function setUrl($url) {
		$this->url = $url;
	}


	/**
	 * @return string
	 */
	public function getDuration() {
		return $this->duration;
	}

	/**
	 * @param string $duration
	 */
	public function setDuration($duration) {
		$this->duration = $duration;
	}


	/**
	 * @return float
	 */
	public function getRating() {
		return $this->rating;
	}

	/**
	 * @param float $rating
	 */
	public function setRating($rating) {
		$this->rating = $rating;
	}


	/**
	 * @return int
	 */
	public function getRatingCount() {
		return $this->ratingCount;
	}

	/**
	 * @param int $ratingCount
	 */
	public function setRatingCount($ratingCount) {
		$this->ratingCount = $ratingCount;
	}


	/**
	 * @return \DateTime
	 */
	public function getPublishDate() {
		return $this->publishDate;
	}

	/**
	 * @param \DateTime $publishDate
	 */
	public function setPublishDate($publishDate) {
		$this->publishDate = $publishDate;
	}


	/**
	 * @return string
	 */
	public function getEmbed() {
		return $this->embed;
	}

	/**
	 * @param string $embed
	 */
	public function setEmbed($embed) {
		$this->embed = $embed;
	}


	/**
	 * @return array
	 */
	public function getThumbs() {
		return $this->thumbs;
	}

	/**
	 * @param string $thumb
	 */
	public function addThumb($thumb) {
		$this->thumbs[] = $thumb;
	}

	/**
	 * @param string $thumb
	 */
	public function removeThumb($thumb) {
		if ($key = array_search($this->thumbs)) {
			unset($this->thumbs[$key]);
		}
	}

	/**
	 * @param array $thumbs
	 */
	public function setThumbs($thumbs) {
		$this->thumbs = $thumbs;
	}


	/**
	 * @return array
	 */
	public function getTags() {
		return $this->tags;
	}

	/**
	 * @param string $tag
	 */
	public function addTag($tag) {
		$this->tags[] = $tag;
	}

	/**
	 * @param string $tag
	 */
	public function removeTag($tag) {
		if ($key = array_search($this->tags)) {
			unset($this->tags[$key]);
		}
	}

	/**
	 * @param array $tags
	 */
	public function setTags($tags) {
		$this->tags = $tags;
	}


	/**
	 * @return array
	 */
	public function getPornstars() {
		return $this->pornstars;
	}

	/**
	 * @param string $pornstar
	 */
	public function addPornstar($pornstar) {
		$this->pornstars[] = $pornstar;
	}

	/**
	 * @param string $pornstar
	 */
	public function removePornstar($pornstar) {
		if ($key = array_search($this->pornstars)) {
			unset($this->pornstars[$key]);
		}
	}

	/**
	 * @param array $pornstars
	 */
	public function setPornstars($pornstars) {
		$this->pornstars = $pornstars;
	}


	public function __construct($source, $videoId) {
		$this->source = $source;
		$this->id = $videoId;
	}


}
