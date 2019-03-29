<?php 
/*
This script takes the information in the events-data-* files and trains the Naive Bayes classifyer from those two files. 
*/
echo "starting\n";
require_once('../vendor/autoload.php');
require_once('../vendor/simple_html_dom/simple_html_dom.php');
//Open the information files. 
$myfileyes = fopen("events-data-yes-food.txt", "r") or die("Unable to open file!");
$myfileno = fopen("events-data-no-food.txt", "r") or die("Unable to open file!");

//We only want to create the classifier once. 
$classifier = new \Niiknow\Bayes(); //Create a classifier
$cnt = 0;
if ($myfileyes) {
    while (($buffer = fgets($myfileyes, 4096)) !== false) {
    	echo $buffer;
        $classifier->learn($buffer, 1);
        $cnt++;
    }
    if (!feof($myfileyes)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($myfileyes);
    echo "Trained on ".$cnt." lines of free food data\n";
}
$cnt = 0;
if ($myfileno) {
    while (($buffer = fgets($myfileno, 4096)) !== false) {
        $classifier->learn($buffer, 0);
        $cnt++;
    }
    if (!feof($myfileno)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($myfileno);
    echo "Trained on ".$cnt." lines of no free food data\n";
}
//This is a test:
echo $classifier->categorize('no shit poo asdfa sdfas dfasdf asdf dfsd f asd asda2 asda w asd asd');

?>