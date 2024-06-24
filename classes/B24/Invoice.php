<?php

namespace WhiteList\B24;

use WhiteList\CRest;

class Invoice extends BaseEntity
{
	private string $entityPrefix = "crm.item.";

	private int $entityTypeId = 31;

	public function add(array $fields = []): array {
		return CRest::call($this->entityPrefix . "add", [
			"entityTypeId" => $this->entityPrefix,
			"fields" => $fields
		]);
	}

	public function delete(int $id): array {
		return CRest::call($this->entityPrefix . "delete", [
			"entityTypeId" => $this->entityTypeId,
			"id" => $id
		]);
	}

	public function update(int $id, array $fields): array {
		return CRest::call($this->entityPrefix . "update", [
			"entityTypeId" => $this->entityTypeId,
			"id" => $id,
			"fields" => $fields
		]);
	}

	public function getList($select = [], $order = [], $filter = [], $start = 0): array {
		return CRest::call($this->entityPrefix . "list", [
			"entityTypeId" => $this->entityPrefix,
			"select" => $select,
			"order" => $order,
			"filter" => $filter,
			"start" => $start,
		]);
	}

	public function addAll(array $invoicesFieldsArray): array {
		$arBatch = [];
		foreach ($invoicesFieldsArray as $item) {
			$item["entityTypeId"] = $this->entityTypeId;
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
