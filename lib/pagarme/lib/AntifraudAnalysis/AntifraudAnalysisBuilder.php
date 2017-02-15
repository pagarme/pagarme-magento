<?php

namespace PagarMe\Sdk\AntifraudAnalysis;

trait AntifraudAnalysisBuilder
{
    /**
     * @param array antifraudAnalysisData
     * @return AntifraudAnalysis
     */
    private function buildAntifraudAnalysis($antifraudAnalysisData)
    {
        $antifraudAnalysisData->date_created = new \DateTime(
            $antifraudAnalysisData->date_created
        );
        $antifraudAnalysisData->date_updated = new \DateTime(
            $antifraudAnalysisData->date_updated
        );

        return new AntifraudAnalysis($antifraudAnalysisData);
    }
}
