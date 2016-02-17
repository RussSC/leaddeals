<?php
class PodcastsUser extends AppModel {
	public $belongsTo = ['User', 'Podcast'];
}