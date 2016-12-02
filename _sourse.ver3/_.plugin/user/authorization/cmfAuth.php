<?php

cmfLoad('user/authorization/cmfAuthSession');

abstract class cmfAuth {

    private $name = null;
    private $data = null;
    private $session = null;
    private $db = null;
    private $ses = null;
    private $ban = null;

    public function __construct() {
        $this->setSession(new cmfAuthSession($this, $this->getName()));
        if ($this->is()) {
            $this->sessionUpdate();
        } else {
            $this->sessionRemove();
        }
    }

    // ��� ������
    /* protected function getName() {
      return $this->name;
      } */
    abstract protected function getName();

    // ���������� ������
    private function setSession($session) {
        $this->session = $session;
    }

    private function session() {
        return $this->session;
    }

    // ���������� ������ ������
    protected function sessionUpdate() {

    }

    // �������� ������ ������
    protected function sessionRemove() {

    }

    // id ������������
    public function setId($id) {
        return $this->session()->setId($id);
    }

    public function getId() {
        return (int) $this->session()->getId();
    }

    // ������ ������������
    public function setData($data) {
        $this->data = $data;
    }

    protected function getData() {
        return $this->data;
    }

    protected function set($k, $v) {
        $this->data[$k] = $v;
    }

    public function get($v) {
        return get($this->data, $v);
    }

    public function __get($value) {
        return $value == 'all' ? $this->data : get($this->data, $value);
    }

    // ������������ ��������� ������������
    abstract protected function getFields();

    abstract public function getFieldsParam();

    // ������� ������
    protected function getDb() {
        return db_user;
    }

    // ������ � ����
    protected function getWhere() {
        return array(1);
    }

    public function is() {
        if (!$this->getId())
            return false;
        static $is = null;
        if ($is === null) {
            $is = $this->isAuth();
        }
        return $is;
    }

    public function reset() {
        $this->isAuth(false);
        $this->sessionUpdate();
    }

    private function isAuth($init = true) {
        if ($init and !$this->session()->isAuth()) {
            return false;
        }

        $row = cmfRegister::getSql()->placeholder("SELECT `isIp`, ?fields FROM ?t WHERE id=? AND `visible`='yes' AND `register`='yes'", $this->getFields(), $this->getDb(), $this->getId())
                ->fetchAssoc();
        if (!$row) {
            $this->session()->remove();
            return false;
        }
        $row = array_merge($row, $this->getParam());
        $this->setData($row);

        if ($init) {
            $this->session()->init($row);
            return true;
        }
    }

    // ��� ������
    static public function hash($password) {
        return sha1($password . cmfSalt);
    }

    // �����������
    public function select($login, $password) {
        $password = self::hash($password);
        $row = cmfRegister::getSql()->placeholder("SELECT `isIp`, ?fields FROM ?t WHERE ?w AND `login`=? AND `password`=? AND visible='yes' AND register='yes' AND UNIX_TIMESTAMP(banDate)<?", $this->getFields(), $this->getDb(), $this->getWhere(), $login, $password, time())
                ->fetchAssoc();
        if (!$row) {
            $this->setError($login);
            return false;
        }
        $this->setId($row['id']);
        $row = array_merge($row, $this->getParam());
        $this->setData($row);
        $this->session()->init($row);

        cmfRegister::getSql()->add($this->getDb(), array('banCount' => 0), $row['id']);
        return true;
    }

    // ������ �������������� ������
    protected function getParam() {
        $row = cmfRegister::getSql()->placeholder("SELECT data, ?fields FROM ?t WHERE id=?", $this->getFieldsParam(), db_user_data, $this->getId())
                ->fetchAssoc();
        if ($row) {
            foreach (unserialize($row['data']) as $k => $v) {
                if (!isset($row[$k]))
                    $row[$k] = $v;
            }
            unset($row['data']);
            return $row;
        } else {
            return array();
        }
    }

    // ������������
    public function logOut() {
        $this->session()->logOut();
        $this->sessionRemove();
    }

    // ����������� ������ ������-������
    protected function setError($login) {
        $sql = cmfRegister::getSql();
        $row = $sql->placeholder("SELECT `banCount` FROM ?t WHERE `login`=? AND visible='yes' AND register='yes' AND UNIX_TIMESTAMP(banDate)<?", $this->getDb(), $login, time())
                ->fetchAssoc();
        if ($row) {
            $error = $row['banCount'] + 1;
            $time = time();
            if ($error > 10) {

                if ($error < 15)
                    $time = time() + 60 * ($error - 10);
                else if ($error < 20)
                    $time = time() + 2 * 60 * ($error - 10);
                else if ($error < 30)
                    $time = time() + 3 * 60 * ($error - 10);
                else
                    $time = time() + 4 * 60 * ($error - 10);
                if ($error >= 20) {
                    $data = array('date' => date('d.m.y H:i:s'),
                        'user' => $login,
                        'ip' => cmfGetIpInt(),
                        'proxy' => cmfGetProxyInt());
                }
            }
            $sql->placeholder("UPDATE ?t SET ?% WHERE ?w", $this->getDb(), array('banCount' => $error, 'banDate' => date('Y-m-d H:i:s', $time)), array('login' => $login));
        }
    }

}

?>