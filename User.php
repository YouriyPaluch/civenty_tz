<?php

namespace Civenty;

use mysqli;

class User
{
    /**
     * @var mysqli
     */
    protected $_db;

    /**
     * construct and access from DB
     */
    public function __construct()
    {
        $this->_db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->_db->connect_errno != 0) {
            die($this->_db->connect_error);
        }
    }

    /**
     * top up balance for phome
     * @param array $number
     * @param $sum
     */
    public function topUpPhone(array $number, $sum)
    {
        if ($sum >= 100 || $sum < 0 || empty($sum)) {
            exit('Incorrect sum');
        }
        if(count($number)<3){
            exit('Incorrect number');
        }
        $countryCode = $number['countryCode'];
        $operatorCode = $number['operatorCode'];
        $numberPhone = $number['number'];
        $queryCountryCode = "SELECT id FROM country_codes WHERE code = $countryCode;";
        $queryOperatorCode = "SELECT id FROM operator_codes WHERE code = $operatorCode;";
        $countryCodeId = mysqli_fetch_assoc($this->_db->query($queryCountryCode))['id'];
        $operatorCodeId = mysqli_fetch_assoc($this->_db->query($queryOperatorCode))['id'];
        if (!$this->_db->query($queryCountryCode)) {
            die($this->_db->error);//TODO exeption
        }
        if (!$this->_db->query($queryOperatorCode)) {
            die($this->_db->error);//TODO exeption
        }
        $query = "UPDATE telephons SET balance = balance + $sum WHERE country_code_id='$countryCodeId' AND operator_code_id='$operatorCodeId' AND telephons.number='$numberPhone';";
        if (!$this->_db->query($query)) {
            die($this->_db->error);//TODO exeption
        }
    }

    /**
     * add new user
     * @param array $user
     */
    public function addUser(array $user)
    {
        if(count($user)<3){
            exit('Incorrect user');
        }
        $name = $user['name'];
        $birthday = $user['birthday'];
        $query = "INSERT INTO users values (null, '$name', '$birthday');";
        if (!$this->_db->query($query)) {
            die($this->_db->error);//TODO exeption
        }
        $idQuery = "SELECT id FROM users WHERE name like '$name';";
        $id = mysqli_fetch_assoc($this->_db->query($idQuery))['id'];
        if (!$this->_db->query($idQuery)) {
            die($this->_db->error);//TODO exeption
        }
        $phoneNumbers = $user['phoneNumbers'];
        $this->addUserPhone($phoneNumbers, $id);
    }

    /**
     * get user data
     * @param int $id
     * @return array
     */
    public function getUser(int $id)
    {
        $query = "select users.name, users.birthday, concat(country_codes.code,'-',operator_codes.code,'-',telephons.number) as phone_number, telephons.balance from users left outer join telephons on telephons.user_id = users.id LEFT OUTER join operator_codes ON telephons.operator_code_id=operator_codes.id LEFT OUTER JOIN country_codes ON country_codes.id = telephons.country_code_id WHERE users.id = $id;";
        $result = $this->_db->query($query);
        if (!$result) {
            die($this->_db->error);//TODO exeption
        }
        return mysqli_fetch_all($result);
    }

    /**
     * add new phone number for user
     * @param array $userPhones
     * @param $id+
     */
    public function addUserPhone(array $userPhones, $id)
    {
        if(count($userPhones)<4){
            exit('Incorrect user phones');
        }
        $userData = $this->getUser($id);
        if (count($userData) >= 3) {
            exit ('User can not have mor three numbers');
        }
        for ($j = 1, $i = 0; $j <= count($userPhones); $j++, $i++) {
            $balance = $userPhones[$i]['balance'];
            $number = $userPhones[$i]['number'];
            if (strlen($number) > 7) {
                exit ('Very log number');
            }
            $countryCode = $userPhones[$i]['countryCode'];
            $operatorCode = $userPhones[$i]['operatorCode'];
            $phonesQuery = "INSERT INTO telephons values (null, '$balance', $number, $countryCode, $operatorCode, $id);";
            if (!$this->_db->query($phonesQuery)) {
                die($this->_db->error);//TODO exeption
            }
        }
    }

    /**
     * delete all user data
     * @param int $id
     */
    public function deleteUser(int $id)
    {
        $query = "DELETE FROM telephons WHERE user_id = $id;DELETE FROM users WHERE id = $id;";
        if (!$this->_db->multi_query($query)) {
            die($this->_db->error);//TODO exeption
        }
    }
}