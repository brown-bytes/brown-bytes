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

//Write the classifier to a file:
$output_weights = fopen("classifier_weights.txt", "w") or die("Unable to open file!");
fwrite($output_weights, $classifier->toJson());
echo "Wrote weights to file classifier_weights.txt\n";
fclose($output_weights);


//This is a test:
/*if($classifier->categorize('Ali Momeni is a Professor of Practice within the Brown Arts Initiative and Data Science Initiative. His research interest includes educational technologies, human-computer interaction for performative applications of robotics, playful urban interventions, interactive projection performance, machine learning for artists and designers, interactive tools or storytelling and experiential learning, mobile and hybrid musical instruments, and the intersection of sound, music and health. Registration is free: ')) {
    echo "YESSS!\n";
} else {
    echo "NOOOPE!\n";
}*/

?>