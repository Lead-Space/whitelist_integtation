<?php
require_once "../vendor/autoload.php";
require_once "../include/consts.php";
require_once "../include/functions.php";


use Domos\CRest;



out(CRest::call("crm.type.list", []));
//$binanceUserList = json_decode(file_get_contents("../integration_files/Passport_users_Binance.json"), true);
//$cachedUsers = getDataFromCache("users.json");
//$testCache = [
//	16424 => [
//		"kyc_profile_id" => 15717,
//		"user_id" => 16424,
//		"registration_date" => "2023-12-25",
//		"kyc_1_passed_date" => null,
//		"email" => "jk5515@yandex.ru",
//		"is_telegram_nickname" => true
//	],
//	16423 => [
//		"kyc_profile_id" => 15717,
//		"user_id" => 16424,
//		"registration_date" => "2023-12-25",
//		"kyc_1_passed_date" => null,
//		"email" => "jk5515@yandex.ru",
//		"is_telegram_nickname" => true
//	],
//];
//setCacheData("users.json", $testCache);



