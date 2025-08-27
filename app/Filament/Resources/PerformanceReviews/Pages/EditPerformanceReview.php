<?php

namespace App\Filament\Resources\PerformanceReviews\Pages;

use App\Filament\Resources\PerformanceReviews\PerformanceReviewResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPerformanceReview extends EditRecord
{
    protected static string $resource = PerformanceReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    /**
     * Memodifikasi data formulir sebelum diperbarui di database.
     * Fungsi ini akan menghitung ulang final_score secara otomatis.
     */
    protected function afterSave(): void
    {
        $review = $this->record;
        $review->load('kpis'); // Muat ulang relasi KPI yang baru saja disimpan

        if ($review->kpis->isNotEmpty()) {
            $finalScore = 0;
            foreach ($review->kpis as $kpi) {
                $score = (float) ($kpi->score ?? 0);
                $weight = (float) ($kpi->weight ?? 0);
                // Rumus: Skor Akhir = Σ (skor * (bobot / 100))
                $finalScore += $score * ($weight / 100);
            }
            
            // Update record dengan skor akhir
            $review->final_score = $finalScore;
            $review->save();
        }
    }
}
