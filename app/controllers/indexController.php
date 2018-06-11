<?php
use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
    public function indexAction() {
        // Add CSS resources
        $this->assets->addCss('css/bootstrap.min.css');
        //$this->assets->addCss('css/index.css');

        // And JavaScript resources
        //$this->assets->addJs('js/jquery.js');
        $this->assets->addJs('js/bootstrap.min.js');
    }
}

?>