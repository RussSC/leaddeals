<?php
App::uses('Email', 'Utility');
App::uses('Hash', 'Utility');
App::uses('Security', 'Utility');

class User extends AppModel {
	public $actsAs = [
		'Uploadable.FieldUpload' => [
			'thumbnail' => []
		]
	];

	public $hasAndBelongsToMany = [
		'Podcast',
		'PodcastEpisode',
	];

	public function beforeSave($options = []) {
		if (!empty($this->data[$this->alias])) {
			$data =& $this->data[$this->alias];
			
			// Update Password
			if (!empty($data['new_password'])) {
				if (empty($data['new_password_hash'])) {
					App::uses('Security', 'Utility');
					$hash = Security::hash($data['new_password'], null, true);
					if (!empty($hash)) {
						$data['new_password_hash'] = $hash;
					} else {
						$this->invalidate('new_password', 'Password has not been encrypted');
						return false;
					}
				}
				$data['password'] = $data['new_password_hash'];
			}

			// Cleanup Email
			if (!empty($data['email'])) {
				$data['email'] = Email::cleanup($data['email']);
			}
		}
		return parent::beforeSave($options);
	}

	public function isEditor($id, $currentUserId) {
		if ($this->isAdmin($currentUserId)) {
			return true;
		}
		$user = $this->read('id', $id);
		return $currentUserId == $user[$this->alias][$this->primaryKey];
	}

	public function isAdmin($id) {
		$result = $this->find('first', [
			'recursive' => -1,
			'conditions' => [
				$this->escapeField() => $id,
				$this->escapeField('is_admin') => 1,
			]
		]);
		return !empty($result);
	}
}