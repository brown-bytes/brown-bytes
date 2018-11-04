<?php

//This is the file for validators with the input for the website

use Phalcon\Validation;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Callback;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Uniqueness;

class RegisterValidator extends Validation {
	//The validator for the register page
	public function initialize() {
		$this->add(
			'name',
			new PresenceOf(
				[
					'message' => 'Name is required'
				]
			)
		);
		$this->add(
			'name',
			new Uniqueness(
				[
					'convert' => function($string) {
						return array('name'=> strtolower($string['name']));
					},
					'model' => new Users(),
					'message' => 'This name is already taken'
				]
			)
		);
		$this->add(
			'name',
			new Callback(
				[
					'callback' => function($data) {
						return (strpos($data->name, ' ') !== false);
						//checking for spaces to verify name
					},
					'message' => 'Please include first name and last name separated by a space.'
				]
			)
		);
		$this->add(
			'email',
			new PresenceOf(
				[
					'message' => 'Email is required'
				]
			)
		);
		$this->add(
			'email',
			new Email(
				[
					'message' => 'Not a valid email format'
				]
			)
		);
		$this->add(
			'email',
			new Uniqueness(
				[
					'model' => new Users(),
					'message' => 'There is already an account with this email. Try resetting your password.'
				]
			)
		);
		$this->add(
			'email',
			new Callback(
				[
					'callback' => function($data) {
						return (strpos($data->email, '@brown.edu') !== false);
						//checking to see if the email is actually from Brown
					},
					'message' => 'You must use your official Brown email to sign up for Brown Bytes.'
				]
			)
		);
	}
}
?>