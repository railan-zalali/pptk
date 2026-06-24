<?php

namespace App\Services\RuleEngine;

/**
 * Contract untuk semua Rule dalam Insight Engine.
 * Setiap rule menerima data mentah dan mengembalikan InsightResult atau null.
 */
interface InsightRuleContract
{
    /**
     * Evaluasi data dan hasilkan InsightResult.
     *
     * @param  mixed  $data  Data yang akan dievaluasi (nilai numerik, array, dsb.)
     * @return InsightResult|null  Null jika rule tidak relevan / tidak perlu alert
     */
    public function evaluate(mixed $data): ?InsightResult;

    /**
     * Nama unik rule ini (untuk logging dan debugging).
     */
    public function getName(): string;
}
