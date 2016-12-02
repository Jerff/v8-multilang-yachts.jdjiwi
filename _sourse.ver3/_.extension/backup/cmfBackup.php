<?php


class cmfBackup {

    private static $block = array();
    private static $isRun = true;

    static public function setBlok($block) {
        self::$block = $block;
        self::$isRun = false;
    }


    // создание дампа
    static public function export($file, $_table, $_noData=null) {
        $gz = gzopen($file, 'w9');
        gzwrite($gz, self::exportStart());
        foreach($_table as $k=>$list) {
            if(is_array($list)) {
                gzwrite($gz, "\n\n#export block [$k]");
                foreach($list as $table) {
                    self::_export($gz, $table, $_noData);
                }
            } else {
                self::_export($gz, $list, $_noData);
            }
        }
        gzclose($gz);
    }
    static private function _export($gz, $table, $_noData) {
        gzwrite($gz, self::exportShowTable($table));
        if(!isset($_noData[$table])) {
            gzwrite($gz, self::exportData($table));
        }
    }


    static public function exportStart() {
        $sql = cmfRegister::getSql();
        $sql->query("SET SESSION SQL_QUOTE_SHOW_CREATE = 1");
        return '#
# oiklbkljs SQL Dump
# version 2.0      #
#
# Время создания: '. strftime('%B %d %Y г., %H:%M') .'
# Версия сервера: '. $sql->getClientVersion() .'
# Версия PHP: '. phpversion() .'

#
';
    }

    static public function exportShowTable($table) {
        $sql = cmfRegister::getSql();
        $row = $sql->placeholder("SHOW CREATE TABLE ?t", $table)->fetchAssoc();
        $create = $row['Create Table'] .';';
        return "

# --------------------------------------------------------

#
# Структура таблицы {$table}
#

DROP TABLE IF EXISTS {$table};
{$create}

#
# Дамп данных таблицы {$table}
#
";
    }

    static public function exportData($table) {
        $sql = cmfRegister::getSql();
        $res = $sql->placeholder("SELECT * FROM ?t", $table);
        $dump = '';
        while($row = $res->fetchAssoc()) {
			$dump .= "\n". $sql->getQuery("INSERT INTO ?t SET ?%", $table, $row) .';';
		}
		return $dump;
	}

	// импорт sql кода
	static public function import($file) {
		$sql = cmfRegister::getSql();
        $gz = gzopen($file, 'r9');
		$command = array('#', 'INSERT', 'DROP', 'CREATE');
		$i = 0;
		$old = '';
		while(++$i) {
			$import = gzread($gz, 60000);
			if(!$import) break;
			$import = explode("\n", $old . $import);
			$old = '';
			$len = count($import)-1;
			while(list($k, $line) = each($import)) if($line) {
				if($len==$k) {
					$old = $line;
					break;
				}
				foreach($command as $value)
					if(stripos($line, $value)===0) {
						if(!empty($query)) {
							self::query($query);
						}
						$query = '';
						break;
					}
				if(isset($query)) $query .= $line;
			}
        }
        gzclose($gz);
        if($old) {
            $line = $old;
            foreach($command as $value)
                if(stripos($line, $value)===0) {
                    if(!empty($query)) {
                        self::query($query);
                    }
                    $query = '';
                    break;
                }
            if(isset($query)) $query .= $line;
        }
        if(!empty($query)) {
            self::query($query);
    	}
		$sql->optimize();
	}


	static public function query($query) {
		if(stripos($query, '#')===0) {
    		if(preg_match('~#export block \[[a-z]+\]~', $query)) {
		        $block = preg_replace('~(#export block \[([a-z]+)\])~', '$2', $query);
		        self::$isRun = isset(self::$block[$block]);
		    }
    	} elseif(self::$isRun) {
            cmfRegister::getSql()->query($query);
    	}
	}

}

?>
