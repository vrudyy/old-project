<?php

abstract class Lang{
    
    protected $words = [];
    
    public function __construct() {
        $this->setWords();
    }
    
    protected abstract function setWords();


    public function getWords(){
        return $this->words;
    }
}

