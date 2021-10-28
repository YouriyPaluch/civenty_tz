<?php
/**
 * get random user name
 * @return string
 */
function randomUserName()
{
    $namelength = rand(5, 8);
    $name = '';
    $alphabet = array(
        'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r',
        's', 't', 'u', 'v', 'w', 'x', 'y', 'z'
    );
    for ($i = 1; $i <= $namelength; $i++) {
        $letter = rand(0, 25);
        $name .= $alphabet[$letter];
    }
    $name = ucfirst($name);
    return $name;
}

/**
 * get count phone numbers
 * @return int
 */
function randomCountPhone()
{
    return rand(1, 3);
}

/**
 * get operator code
 * @return int
 */
function randomCodeOperator()
{
    return rand(1, 4);
}

/**
 * get telephone numbers
 * @return array
 */
function randomPhoneNumber()
{    $countPhone = randomCountPhone();
    for($j = 1; $j<=$countPhone; $j++) {
        $phoneNumber['countryCode']=1;
        $phoneNumber['operatorCode'] = randomCodeOperator();
        $number = null;
        for ($i = 1; $i <= 7; $i++) {
            if($i==1){
                $number .= rand(1, 9);
            }else {
                $number .= rand(0, 9);
            }
        }
        $phoneNumber['number']=$number;
        $phoneNumber['balance']=randomPhoneBalance();

        $allPhones[] = $phoneNumber;
    }
    return $allPhones;
}

/**
 * get phone balance
 * @return float|int
 */
function randomPhoneBalance()
{
    $positive =rand(1,2);
    if ($positive == 1) {
        $balance = rand(0, 15000)/100;
    } else {
        $balance = rand(-5000, 0)/100;
    }
    return $balance;
}

/**
 * get user birthday date
 * @return false|string
 */
function randomBirthday()
{
    $timestamp = rand(162055681, 1362055681);
    $birthday = date("Y-m-d", $timestamp);
    return $birthday;
}

/**
 * create user
 * @return array
 */
function createUser()
{
    $userName = randomUserName();
    $phoneNumber = randomPhoneNumber();
    $birthday = randomBirthday();
    $user = [
        'name'=>$userName,
        'birthday'=>$birthday,
        'phoneNumbers'=>$phoneNumber,
    ];
    return $user;
}