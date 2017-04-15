<?php
class PodcastEpisodesUser extends AppModel {
	public $belongsTo = ['User', 'PodcastEpisode'];
}