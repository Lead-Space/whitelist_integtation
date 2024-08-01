<?php

use WhiteList\CRest;

function out($var, $var_name = ''): void {
	echo '<pre style="outline: 1px dashed red;padding:5px;margin:10px;color:white;background:black;">';
	if (!empty($var_name)) {
		echo '<h3>' . $var_name . '</h3>';
	}

	if (is_string($var)) {
		$var = htmlspecialchars($var);
	}
	print_r($var);
	echo '</pre>';
}

function setLog(mixed $arData, string $fileName = 'default', string $title = ''): void {
	$logsDir = __DIR__ . '/../logs/';
	$path = $logsDir . date("Y-m-d/H") . '/';

	if (!file_exists($path)) {
		@mkdir($path, 0775, true);
	}

	$logs = array_diff(scandir($logsDir), [".", ".."]);
	foreach ($logs as $log) {
		if (strtotime($log) < strtotime("-1 month")) {
			deleteOutdatedLogDir($logsDir . $log);
		}
	}

	$path .= $fileName . '.log';
	$log = "\n------------------------\n";
	$log .= date("Y.m.d H:i:s") . "\n";
	$log .= (strlen($title) > 0 ? $title : 'DEBUG') . "\n";
	$log .= print_r($arData, 1);
	$log .= "\n------------------------\n";
	file_put_contents($path, $log, FILE_APPEND);
}

function deleteOutdatedLogDir($dir): void {
	$files = glob($dir . '/*');
	foreach ($files as $file) {
		if (is_dir($file)) {
			deleteOutdatedLogDir($file);
		} else {
			unlink($file);
		}
	}
	rmdir($dir);
}

function formatDataForBatch(string $method, array $params = []): string {
	return $method . "?" . urldecode(http_build_query($params));
}

function getDataFromCache(string $fileName): array {
	return json_decode(file_get_contents("../cache/$fileName"), true);
}

function executeMainBatch(array $mainBatch): void {
	$chunkedBatch = array_chunk($mainBatch, CRest::BATCH_COUNT);
	foreach ($chunkedBatch as $batch) {
		CRest::call("batch", ["cmd" => $batch]);
		sleep(2);
	}
}

function setCacheData(string $fileName, array $data): void {
	file_put_contents("../cache/$fileName", json_encode($data));
}

function getIntegrationDataFromFile(string $fileName) {
	return json_decode(file_get_contents("../integration_files/$fileName"), true);
}