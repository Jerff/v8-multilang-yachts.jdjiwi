<?php

cmfDebug::init();
cmfLoad('debug/cmfException');
cmfLoad('debug/function');
class cmfDebug {

	private static $error = false;
	private static $sql = false;
	private static $explain = false;

	private static $sqlTime = false;
	private static $sqlCount = 0;

	private static $isSql = true;
	private static $log = '';

	private static $time = null;

	private static $isEnd = null;

	// инициализация работы
	public static function init() {
		self::$time = cmfGetMicrotime();
		set_error_handler(array('cmfDebug', 'errorHandler'));
		register_shutdown_function(array('cmfDebug', 'end'));
	}


	public static function destroy() {
		self::setError(false);
		self::setSql(false);
		self::setExplain(false);

		self::$log = null;
		self::$time = null;
	}


	public static function getMemory() {
		self::$log .= "\nmemory: ". (memory_get_usage()/1024/1024);
	}



	// отладка ошибок php
	// вернуть режим отладки ошибок php
	public static function isError() {
		return self::$error;
	}

	// установить режим отладки ошибок php
	public static function setError($status=true) {
		if($status) {
			error_reporting(E_ALL);
		} else {
			error_reporting(0);
		}
		self::$error = (bool)$status;
	}

	// добавить в лог ошибок php
	public static function addError($error, $message=null) {
		if(self::$error) {
			self::$log .= "\n". ($message ? $error .': '. $message : $error);
		}
	}

	public static function addLog($message) {
		if(self::$error or self::isSql()) {
			if(self::$isEnd) {
                echo ('<pre>'. $message .'</pre>');
			} else {
				self::$log .= "\n". $message;
			}
		}
	}



	// отладка sql
	// вернуть режим отладки sql запросов к базе
	public static function isSql() {
		return self::$sql && self::$isSql;
	}
	public static function sqlLogOn() {
		self::$isSql = true;
	}
	public static function sqlLogOff() {
		self::$isSql = false;
	}

	// установить режим отладки sql запросов к базе
	public static function setSql($status=true) {
		self::$sql = (bool)$status;
	}

	// добавить в лог запросов к базе
	public static function addSql($message='SELECT 1', $time=null) {
		if(!self::isSql()) return;
		$page = cmfPages::getItem();
		$message = (++self::$sqlCount) .' '. round($time, 8) ." ". htmlspecialchars($message);
		if(cmfPages::isMain($page)) {
		 	$message .=" [$page]";
		} else {
			$main = cmfPages::getMain($page);
			$message .=" [$main] [$page]";
		}
		self::addLog($message);
		self::$sqlTime += $time;

		//self::getMemory();
	}



	// отладка sql explain
	// вернуть режим отладки оптимизации sql запросов к базе
	public static function isExplain() {
		return self::$explain;
	}
	// установить режим отладки оптимизации sql запросов к базе
	public static function setExplain($status=true) {
		self::$explain = (bool)$status;
	}
	// добавить в лог запросов к базе
	public static function addExplain($message) {
		if(self::isExplain()) self::$log .= "\n". htmlspecialchars($message);
	}



	// вернуть весь лог скрипта
	public static function end() {
		echo self::getLog();
	}



	public static function getLog() {
        static $run = false;
		if($run) return;
		$run = true;

		if((!self::$error and !self::$sql and !self::$explain)) return null;
		$str = "<pre id='Debug'>\n";
		$str .= "<b>time</b> = ". (cmfGetMicrotime()-self::$time);

		if(self::$sql) {
			$str .= "\n<b>SQL_COUNT</b> = ". self::$sqlCount;
			$str .= "\n<b>SQL_TIME</b> = ". self::$sqlTime;
		}

		$str .= "\n<b>LOG</b> = ". self::$log;
		return $str .'</pre>';
		return '';
	}



	public static function errorHandler($errno, $errmsg, $filename, $linenum, $vars) {

		if(class_exists('cmfPages')) {
    		$main = cmfPages::getMain();
    		$item = cmfPages::getItem();
    		$url = cmfPages::getUri();
		} else {
		    $main = $item = $url = '';
		}

		$errortype = array (
			E_ERROR					=> 'Error',
			E_WARNING				=> 'Warning',
			E_PARSE					=> 'Parsing Error',
			E_NOTICE				=> 'Notice',
			E_CORE_ERROR			=> 'Core Error',
			E_CORE_WARNING			=> 'Core Warning',
			E_COMPILE_ERROR			=> 'Compile Error',
			E_COMPILE_WARNING		=> 'Compile Warning',
			E_USER_ERROR			=> 'User Error',
			E_USER_WARNING			=> 'User Warning',
			E_USER_NOTICE			=> 'User Notice',
			E_STRICT				=> 'Runtime Notice',
			E_RECOVERABLE_ERROR		=> 'Catchable Fatal Error'
		);
		$message="$errno <b>{$errortype[$errno]}</b> $filename => {$linenum}: {$errmsg} [{$main}] => [{$item}] uri={$url}";
		//echo($message);
		self::addErrorLog($message);
		self::addError($message);
	}



	public static function addErrorLog($message) {
		return;
		$message = "\n". date("Y-m-d H:i:s (T): ") .' '. $message;

		$dir = cmfData .'errorLog/'. date('Y-m') .'/';
		if(!is_dir($dir)) {
			if(!cmfDir::mkdir($dir)) return;
		}

		$f = fopen($dir . date('Y-m-d (H)') .'.log', 'a');
		fwrite($f, $message ."\n\n\n");
		fclose($f);
	}

}

?>
