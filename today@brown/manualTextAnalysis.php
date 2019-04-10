<?php

require_once('../vendor/autoload.php');

class manualClassifier {
	private $words = array();
	//This function creates an instance of the manualclassifier and gets all the positive words (only included in strings that have free food) in the object.
	function __construct() {
		//Open positive food words file:
		$words = fopen("../today@brown/positiveWords.txt", "r") or die("Unable to open file!");
		$json_words = fread($words,filesize("../today@brown/positiveWords.txt"));
		fclose($words);
		//assign them to a local variable
		$this->words = json_decode($json_words)->words;
	}

	//This function checks whether a string includes the free food words loaded in $words
	public function classify($string = "") {
		if($string) {
			//Split the words at spaces
			$string_words = preg_split("/[\s,]+/", $string);
			//This checks if there is an intersect (words appearing in both strings) which means there are free food words in the text
			$intersect = array_intersect($this->words, $string_words);
			if($intersect) {
				return true;
			}
		}
		return false;
	}
	//This function writes to the file that the event was classified as with food to further train the bayes model.
	public function categorize_yes_food($string) {
		$file = fopen("events-data-yes-food.txt", "a") or die("Unable to open file!");
		fwrite($file, $string."\n");
		fclose($file);
	}
	//This function writes to the file that the event was classified as without food to further train the bayes model.
	public function categorize_no_food($string) {
		$file = fopen("events-data-no-food.txt", "a") or die("Unable to open file!");
		fwrite($file, $string."\n");
		fclose($file);
	}
}



?>
