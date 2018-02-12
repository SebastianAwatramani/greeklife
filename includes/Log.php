<?php

////////////////////////////////////////////////////////////////////
// Log() logs errors 											  //
// $filePath: Path to log file.  Should be root/logs/log.txt      //                     
// $logData: the data to log.  Generally $db->getError()          //
////////////////////////////////////////////////////////////////////


Class Log {

	private $filePath;
	private $logBreak;

	public function __construct($filePath) {
		$this->filePath = $filePath;
		$this->logBreak = "\r\n___________________________________________________________________\r\n";
		
	}
	public function writeLog($logData) {

		$date = new DateTime();
		
		$logData = "[datetime: ". $date->getTimestamp() . "]" . "\r\n" . $logData . "\r\n";
		$logData = $logData . $this->logBreak;
		
		try {
			$fileContents = file_get_contents($this->filePath);
			$fileContents .= $logData;
			file_put_contents($this->filePath, $fileContents);
		} catch (Exception $e) {
			return;
		}
	}
}
?>