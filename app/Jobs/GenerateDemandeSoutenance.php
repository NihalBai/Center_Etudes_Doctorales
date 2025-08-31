<?php

namespace App\Jobs;

use App\Models\Demande;
use App\Services\DocumentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateDemandeSoutenance implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $demandeData;
    protected $documentService;

    public function __construct(Demande $demandeData, DocumentService $documentService)
    {
        $this->demandeData = $demandeData;
        $this->documentService = $documentService;
    }

    public function handle()
    {
        return $this->documentService->generateDemandeSoutenance($this->demandeData);
    }
}
