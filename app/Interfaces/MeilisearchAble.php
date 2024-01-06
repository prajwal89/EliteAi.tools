<?php

namespace App\Interfaces;

interface MeilisearchAble
{
    public static function documentsForSearch(?int $id = null, int $batchNo = 0): array;

    public static function documentsForSearchTotalBatches(): int;
}
