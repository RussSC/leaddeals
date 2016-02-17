<?php
class Email {
	public static function cleanup($email) {
		$email = trim(strtolower($email));
		return $email;
	}
}