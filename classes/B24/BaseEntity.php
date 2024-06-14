<?php

namespace Domos\B24;

use Domos\CRest;

abstract class BaseEntity
{
	private string $entityPrefix = "";
	private int $entityTypeId = 0;

	abstract public function getList($select = [], $order = [], $filter = [], $start = 0): array;

	abstract public function add(array $fields = []): array;

	abstract public function delete(int $id): array;

	abstract public function update(int $id, array $fields): array;
}