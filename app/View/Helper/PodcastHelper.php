<?php
class PodcastHelper extends AppHelper {
	public $helpers = ['Html', 'Form', 'Uploadable.FieldUploadImage'];

	public function episodeImage($result, $field, $size, $options = []) {
		$podcastEpisode = !empty($result['PodcastEpisode']) ? $result['PodcastEpisode'] : $result;
		$image = $this->FieldUploadImage->image($podcastEpisode, $field, $size, $options);
		if (empty($image) && !empty($result['Podcast'])) {
			$image = $this->FieldUploadImage->image($result['Podcast'], $field, $size, $options);
		}
		return $image;
	}

	public function episodeTitle($result) {
		$podcastEpisode = !empty($result['PodcastEpisode']) ? $result['PodcastEpisode'] : $result;
		$title = '';
		if (!empty($result['Podcast']['title'])) {
			$title .= $this->Html->tag('span', $result['Podcast']['title'], ['class' => 'podcast-episode-title-podcast-title']);
		}
		$title .= $this->Html->tag('span', $podcastEpisode['title'], ['class' => 'podcast-episode-title-podcast-episode-title']);
		return $this->Html->tag('span', $title, ['class' => 'podcast-episode-title']);
	}

	public function episodeNumber($number) {
		if (is_array($number) && array_key_exists('episode_number', $number)) {
			return $this->episodeNumber($number['episode_number']);
		} else {
			if ($number == round($number)) {
				$number = round($number);
			} 
			return $number;
		}
	}

	public function iTunesUrl($url) {
		return str_replace('https://', 'itms://', $url);
	}
}