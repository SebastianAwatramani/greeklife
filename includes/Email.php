<?php
//This class cannot be finished until server access is setup, as I don't have a local SMTP server
Class Email {
	private $to  = "carl.fuermann@colorado.edu";
	private $from = "greeklife@registrar.colorado.edu";
	private $subject;
	private $partialSid;
	private $society;
	private $body;
	private $error;

	function __construct(&$user) {
		$this->subject = SOCIETY . " " . MEMBERSTATUS ."  Grade Release Request";
		$this->partialSid = "xxxxx" . substr($user->getSid(), 4);

		if(isset($_POST['societySearch'])) {
			$this->society = htmlspecialchars($_POST['societySearch']);
		} else {
			$this->society = "ALL";
		}

		$this->body = ucfirst(SOCIETY) . " Grade Release Form\n\n" .
		"Status: " . ucfirst(MEMBERSTATUS) . "\n\n" .
		"Member Name: {$user->getFullName()} \n\n" .
		"SID: {$this->partialSid}\n\n" .
		ucfirst(SOCIETY) . ": $this->society";

	}
	public function send() {
		//Must wait until live server to send email  For now, just return true

		return true;
	}
	public function getError() {
		return $this->error;
	}
}

?>