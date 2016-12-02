<?php


abstract class _interface_controller_ajax extends driver_interface {


	private $callFuncWrite = '|update|deleteFile|';
	private $callFuncRead = '';
	private $callFuncDelete = '';
	protected function callFuncReadAdd($func) {
		 $this->callFuncRead .= $func .'|';
	}
	protected function callFuncReadDel($func) {
		 $this->callFuncRead = str_replace('|'. $func .'|', '|', $this->callFuncWrite);
	}

	protected function callFuncWriteAdd($func) {
		 $this->callFuncWrite .= $func .'|';
	}
	protected function callFuncWriteDel($func) {
		 $this->callFuncWrite = str_replace('|'. $func .'|', '|', $this->callFuncWrite);
	}

	protected function callFuncDeleteAdd($func) {
		 $this->callFuncDelete .= $func .'|';
	}
	protected function callFuncDeleteDel($func) {
		 $this->callFuncDelete = str_replace('|'. $func .'|', '|', $this->callFuncWrite);
	}

	public function isRule($where) {
		return true;
	}


	// --------------- главная фукнция ajax обработки вызывающая функции модуля ---------------
	private $callFunction = false;
	protected function isCallFunction() {
	    return $this->callFunction;
    }
	public function callFunction($arg) {
		$this->callFunction = true;

		$func = array_shift($arg);
		$function = null;
		foreach(explode('|', $this->callFuncRead) as $f) {
			if($f===$func) {
				$function = 'read';
				break;
			}
		}
		foreach(explode('|', $this->callFuncWrite) as $f) {
			if($f===$func) {
				cmfAccess::isWrite($this);
				$function = 'write';
			}
		}

		foreach(explode('|', $this->callFuncDelete) as $f) {
			if($f===$func) {
				cmfAccess::isDelete($this);
				$function = 'delete';
			}
		}

		if(!$function) {
		    cmfAccess::isControllerRead($this);
		}

		if(method_exists($this, $func)) {
			call_user_func_array(array(&$this, $func), $arg);
			$this->setNoUpdate();
		}
		$this->callFunction = false;
	}

	abstract protected function update();

}

?>
