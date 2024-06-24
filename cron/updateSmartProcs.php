<?php
require_once "../vendor/autoload.php";
require_once "../include/consts.php";
require_once "../include/functions.php";

use WhiteList\CRest;

$cachedUsers = getDataFromCache("users.json");
$cachedSmarts = getDataFromCache("smarts.json");

$updatedUsers = 0;
$mainBatch = [];
foreach (SMARTS_ENTITY_TYPE_FILENAMES as $ENTITY_TYPE_ID => $FILENAME) {
	$userList = getIntegrationDataFromFile($FILENAME);
	foreach ($userList as $user) {
		$userId = $user["user_id"];
		if (!isset($cachedUsers[$userId])) continue;

		$cachedUser = $cachedUsers[$userId];

		$updateNeeded = false;
		if (isset($cachedSmarts[$userId])) {
			$cachedSmart = $cachedSmarts[$userId][$ENTITY_TYPE_ID];
			foreach ($cachedSmart as $field => $value) {
				if ($user[$field] != $value) {
					$updateNeeded = true;
					break;
				}
			}
		} else {
			$updateNeeded = true;
			$cachedSmarts[$userId] = SMARTS_FIELDS_ID;
		}
		if ($updateNeeded) {
			$cachedSmarts[$userId][$ENTITY_TYPE_ID] = $user;
			$newSmartFields = [];
			foreach (SMARTS_FIELDS_ID[$ENTITY_TYPE_ID] as $field => $fieldId) {
				$newSmartFields[$fieldId] = $user[$field];
			}
			$mainBatch[] = formatDataForBatch("crm.item.update", [
				"entityTypeId" => $ENTITY_TYPE_ID,
				"id" => $cachedUser["smart_$ENTITY_TYPE_ID"],
				"fields" => $newSmartFields,
			]);
			$updatedUsers++;
		}
		if ($updatedUsers > MAX_USERS_TO_UPDATE) break;
	}
}
out($mainBatch);
out($cachedSmarts);
executeMainBatch($mainBatch);
setCacheData("smarts.json", $cachedSmarts);
