<?php

cmfLoad('phpMailer/class.phpmailer');
class cmfMail {

	private $charset = 'utf-8';

	private $login = null;
	private $password = null;
	private $host = null;
	private $port = null;

	private $attachment = array();

	public function __construct() {
		$this->loadConfig();
	}


	// ��������� ��������� ������
	// ������ �� ����, �� ����� ���������� ����� ������
	public function loadConfig($config=null) {
		if(!$config) {
			$config = cmfRegister::getSql()->placeholder("SELECT login, password, host, port FROM ?t WHERE id='1'", db_mail_config)
											->fetchAssoc();
		}
		foreach(array('login', 'password', 'host', 'port') as $k) {
			if(isset($config[$k])) {
				$this->$k = $config[$k];
			}
		}
	}

	// �������� ����������
	public function getMailVar($name=null) {
		if(!$_var = cmfCache::get('cmfMail::getMailVar')) {

			$_var = cmfRegister::getSql()->placeholder("SELECT var, value FROM ?t", db_mail_var)
										->fetchRowAll(0, 1);

			cmfCache::set('cmfMail::getMailVar', $_var, 'mail');
		}
		return $name ? get($_var, $name) : $_var;
	}


	// ������ c ����������
	public function addAttachment($path, $name) {
		$this->attachment[$path] = $name;
	}
	public function initAttachment(&$mail) {
		foreach($this->attachment as $path=>$name) {
            if($path===$name) {
                $mail->AddAttachment($path);
            } else {
                $mail->AddAttachment($path, $name);
            }
		}
	}


	public function send($to, $subject, $message) {
		$from = $this->getMailVar('proectSupport');
//		pre3($to, $subject, $message);
		//return;
		$mail             = new PHPMailer();

		$mail->IsSMTP();
		$mail->SMTPAuth		= true;                  // enable SMTP authentication
		$mail->SMTPSecure	= '';                 // sets the prefix to the servier
		$mail->Host			= 'localhost';      // sets GMAIL as the SMTP server
		$mail->Port			= $this->port;                   // set the SMTP port for the GMAIL server

		$mail->CharSet		= $this->charset;

		$mail->Username		= $this->login;  // GMAIL username
		$mail->Password		= $this->password;            // GMAIL password
		//pre($mail);
		//phpinfo();

		//$mail->AddReplyTo("yourusername@gmail.com","First Last");

		$mail->From			= $from;
		$mail->FromName		= "";

		$mail->Subject		= $subject;

		$mail->Body			= $message;                      //HTML Body
		$mail->WordWrap		= 200; // set word wrap

		$mail->AddAddress($to, "");

		$mail->IsHTML(false); // send as HTML

		$this->initAttachment($mail);
		$mail->Send();

	}


	// �������� ������� ������ �� ������
	public function sendTemplates($name, $data, $email) {
		list($header, $content) = cmfRegister::getSql()->placeholder("SELECT header, content FROM ?t WHERE name=?", db_mail_templates, $name)
														->fetchRow();
		if(!$header) $header = $name;

		reset($data);
		while(list($k, $v) = each($data)) {
			$header = str_replace('{'. $k .'}', $v, $header);
			$content = str_replace('{'. $k .'}', $v, $content);
		}

		$data = $this->getMailVar();
		reset($data);
		while(list($k, $v) = each($data)) {
			$header = str_replace('{'. $k .'}', $v, $header);
			$content = str_replace('{'. $k .'}', $v, $content);
		}

		$content = str_replace('{proectUrl}', cmfProjectMain, $content);
		$content = str_replace("\r", '', $content);
		$this->send($email, $header, $content);
	}


	// �������� ������� ������ �� ������� ��������� � �������
	public function sendType($type, $name, $data) {
		if(!$_email = cmfCache::get('cmfMail::sendType'. $type)) {

			$_email = cmfRegister::getSql()->placeholder("SELECT email FROM ?t WHERE `?s`='yes'", db_mail_list, $type)
										->fetchRowAll(0);

			cmfCache::set('cmfMail::sendType'. $type, $_email, 'mail');
		}

		foreach($_email as $email) {
			$this->sendTemplates($name, $data, $email);
		}
	}

}

?>