<?php
require_once "../vendor/autoload.php";
require_once "../include/consts.php";
require_once "../include/functions.php";

use WhiteList\CRest;

$binanceUserList = json_decode(file_get_contents("../integration_files/Passport_users_Binance.json"), true);
$cachedUsers = getDataFromCache("users.json");
$addedUsers = [];
$requestsAmount = 0;
$mainBatch = [];
$updateContactsBatch = [];
foreach ($binanceUserList as $index => $binanceUser) {
	$userId = $binanceUser["user_id"];
	if (isset($cachedUsers[$userId])) {
		$cachedUser = $cachedUsers[$userId];
		$updateNeeded = false;
		foreach (array_keys($binanceUser) as $key) {
			if ($binanceUser[$key] != $cachedUser[$key]) {
				$updateNeeded = true;
				$cachedUser[$key] = $binanceUser[$key];
			}
		}
		$cachedUsers[$userId] = $cachedUser;
		if ($updateNeeded) {
			$updateContactsBatch["contact_update_$userId"] = formatDataForBatch("crm.contact.update", [
				"id" => $cachedUser["contactId"],
				"fields" => [
					CONTACT_WC_ID_FIELD => $binanceUser["user_id"],
					CONTACT_TG_EXIST_FIELD => $binanceUser["is_telegram_nickname"],
					CONTACT_KYC_DATE_FIELD => $binanceUser["kyc_1_passed_date"],
					CONTACT_WC_REG_DATE_FIELD => $binanceUser["registration_date"],
				]
			]);
		}
		continue;
	}

	$cachedUsers[$userId] = $binanceUser;
	$addedUsers[] = $userId;

	$mainBatch["contact_add_$userId"] = formatDataForBatch("crm.contact.add", [
		"fields" => [
			"NAME" => $binanceUser["email"],
			CONTACT_WC_ID_FIELD => $binanceUser["user_id"],
			CONTACT_TG_EXIST_FIELD => $binanceUser["is_telegram_nickname"],
			CONTACT_KYC_DATE_FIELD => $binanceUser["kyc_1_passed_date"],
			CONTACT_WC_REG_DATE_FIELD => $binanceUser["registration_date"],
			"EMAIL" => [
				[
					"VALUE" => $binanceUser["email"],
					"VALUE_TYPE" => "WORK"
				]
			]
		]
	]);
	$requestsAmount++;

	$mainBatch["deal_add_$userId"] = formatDataForBatch("crm.deal.add", [
		"fields" => [
			"CONTACT_ID" => "\$result[contact_add_$userId]",
			"TITLE" => "Сделка " . $binanceUser["email"],
		]
	]);
	$requestsAmount++;

	foreach (SMARTS_ENTITY_TYPE_IDS as $ENTITY_TYPE_NAME => $ENTITY_TYPE_ID) {
		$mainBatch["smart_" . $ENTITY_TYPE_ID . "_$userId"] = formatDataForBatch("crm.item.add", [
			"entityTypeId" => $ENTITY_TYPE_ID,
			"fields" => [
				"title" => $ENTITY_TYPE_NAME . " " . $binanceUser["email"],
				"parentId2" => "\$result[deal_add_$userId]"
			]
		]);
		$requestsAmount++;
		$cachedUsers[$userId]["smart_$ENTITY_TYPE_ID"] = 0;
	}
	if ($requestsAmount > 85) break;
}

$chunkedBatch = array_merge(array_chunk($mainBatch, 45, true), array_chunk($updateContactsBatch, CRest::BATCH_COUNT));
$batchResults = [];
out($chunkedBatch);

foreach ($chunkedBatch as $batch) {
	$batchResult = CRest::call("batch", ["cmd" => $batch])["result"]["result"];
	$batchResults = array_merge($batchResults, $batchResult);
}
foreach ($addedUsers as $addedUser) {
	$cachedUsers[$addedUser]["contactId"] = $batchResults["contact_add_$addedUser"];
	foreach (SMARTS_ENTITY_TYPE_IDS as $ENTITY_TYPE_ID) {
		$cachedUsers[$addedUser]["smart_$ENTITY_TYPE_ID"] = $batchResults["smart_$ENTITY_TYPE_ID" . "_$addedUser"]["item"]["id"];
	}
}

setCacheData("users.json", $cachedUsers);
out($cachedUsers);



