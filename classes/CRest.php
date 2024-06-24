<?php

namespace WhiteList;
class CRest
{
	const BATCH_COUNT = 50;//count batch 1 query
	const TYPE_TRANSPORT = '.json';// json or xml
	const BATCH_LIMIT = 2500; // Entity limit

	public static function call(string $method, array $params): array {
		$webhookURL = Config::get("B24")["WEBHOOK_URL"];
		$queryUrl = $webhookURL . $method . self::TYPE_TRANSPORT;
		$queryData = http_build_query($params);
		$curl = curl_init($queryUrl);
		curl_setopt_array($curl, array(
			CURLOPT_POST => 1,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_POSTFIELDS => $queryData,
		));
		$result = curl_exec($curl);
		curl_close($curl);

		return json_decode($result, true);
	}

	public static function callBatch($arData, $halt = 0): array {
		$arResult = [];
		if(is_array($arData))
		{
			$arDataRest = [];
			$i = 0;
			foreach($arData as $key => $data)
			{
				if(!empty($data[ 'method' ]))
				{
					$i++;
					if(static::BATCH_COUNT >= $i)
					{
						$arDataRest[ 'cmd' ][ $key ] = $data[ 'method' ];
						if(!empty($data[ 'params' ]))
						{
							$arDataRest[ 'cmd' ][ $key ] .= '?' . http_build_query($data[ 'params' ]);
						}
					}
				}
			}
			if(!empty($arDataRest))
			{
				$arDataRest[ 'halt' ] = $halt;
				$arPost = [
					'method' => 'batch',
					'params' => $arDataRest
				];
				$arResult = static::call($arPost["method"], $arPost["params"]);
			}
		}
		return $arResult;
	}
}