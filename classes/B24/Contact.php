<?php

namespace Domos\B24;

use Domos\CRest;

class Contact extends BaseEntity
{
	private array $contactFields = [
		"ID пользователя ТГ" => "UF_CRM_1716988414122",

	];
	private string $entityPrefix = "crm.contact.";
	private int $entityTypeId = 3;

	public function getContactFields(): array {
		return $this->contactFields;
	}

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

	public function getExisted($idList) {
		$tgIdField = $this->contactFields["ID пользователя ТГ"];
		$result = $this->getListAll(
			[
				"ID",
				$tgIdField
			],
			[
				"ID" => "ASC"
			],
			[
				$tgIdField => $idList
			]
		);

		return $result["result"];
	}
}
