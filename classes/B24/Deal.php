<?php


namespace WhiteList\B24;

use WhiteList\CRest;

class Deal extends BaseEntity
{
	private string $entityPrefix = "crm.deal.";
	private int $entityTypeId = 2;

	public function add(array $fields = []): array {
		return CRest::call($this->entityPrefix . "add", [
			"fields" => $fields
		]);
	}

	public function delete(int $id): array {
		return CRest::call($this->entityPrefix . "delete", [
			"id" => $id
		]);
	}

	public function update(int $id, array $fields): array {
		return CRest::call($this->entityPrefix . "update", [
			"id" => $id,
			"fields" => $fields
		]);
	}

	public function getList($select = [], $order = [], $filter = [], $start = 0): array {
		return CRest::call($this->entityPrefix . "list", [
			"select" => $select,
			"order" => $order,
			"filter" => $filter,
			"start" => $start,
		]);
	}

	private function getListAll($select = [], $order = [], $filter = []): array {
		$result = $this->getList(["ID"], ["ID" => "ASC"], $filter, 0);
		$total = $result["total"];

		if ($total > CRest::BATCH_LIMIT) {
			$rowCount = CRest::BATCH_LIMIT / CRest::BATCH_COUNT;
		} else {
			$rowCount = ceil($total / CRest::BATCH_COUNT);
		}

		$arBatch = [];
		for ($i = 0; $i < $rowCount; $i++) {
			$arBatch["get_list_$i"] = [
				"method" => $this->entityPrefix . "list",
				"params" => [
					"select" => $select,
					"order" => $order,
					"filter" => $filter,
					"start" => $i * CRest::BATCH_COUNT,
				],
			];
		}
		return CRest::callBatch($arBatch);
	}

	private function addAll(array $entitiesFieldsArray): array {
		$arBatch = [];
		foreach ($entitiesFieldsArray as $item) {
			$arBatch[] = $this->entityPrefix . "add" . "?" . http_build_query($item);
		}

		$arBatch = array_chunk($arBatch, CRest::BATCH_COUNT);
		$arResult = [
			"result" => [],
			"total" => 0,
		];
		foreach ($arBatch as $batchPart) {
			$tempResult = CRest::callBatch($batchPart);
			$arResult["result"] = array_merge($arResult["result"], $tempResult["result"]["result"]);
			$arResult["total"] .= $tempResult["result"]["result_total"];
		}

		return $arResult;
	}
}