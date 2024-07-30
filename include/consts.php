<?php
const CONTACT_WC_ID_FIELD = "UF_CRM_1718258265774";
const CONTACT_KYC_DATE_FIELD = "UF_CRM_1718258286738";
const CONTACT_WC_REG_DATE_FIELD = "UF_CRM_1718258306016";
const CONTACT_TG_EXIST_FIELD = "UF_CRM_1718258326831";
const MAX_USERS_TO_UPDATE = 50;
const SMARTS_ENTITY_TYPE_IDS = [
	"Паспорт пользователя Binance" => 1032,
	"Балансы Binance" => 1036,
	"Балансы Bybit" => 1040,
	"Данные по сделкам Binance" => 1044,
	"Вывод ДС" => 1048,
	"Данные по сделкам Bybit" => 1052,
	"Паспорт пользователя Bybit" => 1056,
];

const SMARTS_ENTITY_TYPE_FILENAMES = [
	1032 => "Passport_users_Binance.json",
	1036 => "Balance_Binance.json",
	1040 => "Bybit_Balance.json",
	1044 => "sdelki_binance.json",
	1048 => "income_withdraw.json",
	1052 => "sdelki_Bybit.json",
	1056 => "Passport_users_bybit.JSON",
];

const SMARTS_FIELDS_ID = [
	1032 => [
//		"kyc_profile_id" => "",
		"user_id" => "ufCrm8_1718257014525",
		"registration_date" => "ufCrm8_1718257084910",
		"kyc_1_passed_date" => "ufCrm8_1718257045755",
		"email" => "ufCrm8_1718257025175",
		"is_telegram_nickname" => "ufCrm8_1718257068255"
	],
	1036 => [
//		"kyc_profile_id" => "",
		"user_id" => "ufCrm10_1718257204447",
		"date" => "ufCrm10_1718257222458",
		"future_balance" => "ufCrm10_1718257243687",
		"futures_finres" => "ufCrm10_1718257275616",
		"spot_balance" => "ufCrm10_1718257297397",
		"spot_finres" => "ufCrm10_1718257317254",
		"spot_balance_privat" => "ufCrm10_1722343841246",
		"future_balance_privat" => "ufCrm10_1722343766457",
	],
	1040 => [
//		"kyc_profile_id" => "",
		"user_id" => "ufCrm12_1718257375254",
		"date" => "ufCrm12_1718257390320",
		"future_balance" => "ufCrm12_1718257418531",
		"futures_finres" => "ufCrm12_1718257445056",
		"spot_balance" => "ufCrm12_1718257462793",
		"spot_finres" => "ufCrm12_1718257487824",
		"spot_balance_privat" => "ufCrm12_1722343891263",
		"future_balance_privat" => "ufCrm12_1722343881656",
	],
	1044 => [
//		"kyc_profile_id" => "",
		"user_id" => "ufCrm14_1718257655020",
		"date" => "ufCrm14_1718257663479",
		"revenue" => "ufCrm14_1718257683842",
	],
	1048 => [
//		"kyc_profile_id" => "",
		"user_id" => "ufCrm16_1718257813786",
		"date" => "ufCrm16_1718257836688",
		"income_sum" => "ufCrm16_1718257849982",
		"withdraw_sum" => "ufCrm16_1718257862087",
	],
	1052 => [
//		"kyc_profile_id" => "",
		"user_id" => "ufCrm18_1718257737809",
		"date" => "ufCrm18_1718257753137",
		"revenue" => "ufCrm18_1718257769747",
	],
	1056 => [
//		"kyc_profile_id" => "",
		"user_id" => "ufCrm20_1718257588511",
		"created_at" => "ufCrm20_1718257601240",
	],
];
