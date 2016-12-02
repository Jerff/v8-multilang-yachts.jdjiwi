<?php


class cmfException extends Exception {

	function __construct($message, $error) {		$message = $message .': '. $error;
		parent::__construct($message);	}

}

?>