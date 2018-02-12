<?php

Class TextCleaner {	
	private $textToClean;
	
	function __construct($textToClean) {
		$this->textToClean = $textToClean;
	}
	public function getcleanedText() {
		return $this->textToClean;
	}
	public function cleanBadLanguage() {
		$badWords = array('fuck', 'shit', 'piss');
		if(is_array($this->textToClean)) {
			foreach($this->textToClean as $key => $value) {
				for($i = 0; $i < count($badWords); $i++) {
					$this->textToClean[$key] = str_ireplace($badWords[$i], "", $this->textToClean[$key]);
				}
			}
		} else {
			for($i = 0; $i < count($badWords); $i++) {
				$this->textToClean = str_ireplace($badWords[$i], "", $this->textToClean);
			}
		}
	}
	public function spaceTrimmer() {
		if(is_array($this->textToClean)) {
			foreach($this->textToClean as $key => $value) {
				for($i = 0; $i < count($this->textToClean); $i++) {
					$this->textToClean[$key] = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $value);
					trim($this->textToClean[$key]);
				}
			}
		} else {			
			$this->textToClean =  preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $textToClean);
			trim($this->textToClean);		
		}
	}
	public function runHeavyDutyCycle() {
		$this->spaceTrimmer();
		$this->cleanBadLanguage();
	}
}


?>