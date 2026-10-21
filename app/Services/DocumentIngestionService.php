<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\LearningResource;
use App\Models\ResourceChunk;
use App\Models\ResourceFile;
use App\Models\ResourcePage;
use App\Models\ResourceVersion;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class DocumentIngestionService
{
    /**
     * Ingest an uploaded document, compute SHA-256, detect duplicates, and store securely.
     */
    public function ingestUploadedFile(
        LearningResource $resource,
        UploadedFile $file,
        User $uploader,
        ?string $changeNote = 'Ajout de document'
    ): ResourceFile {
        $checksum = hash_file('sha256', $file->getRealPath());

        // Contrôle de détection des doublons
        $existingFile = ResourceFile::where('checksum', $checksum)->first();
        if ($existingFile) {
            throw ValidationException::withMessages([
                'file' => ["Ce document existe déjà dans le système (Empreinte SHA-256 identique : {$checksum})."],
            ]);
        }

        // Création ou récupération d'une version de ressource
        $latestVersion = $resource->latestVersion;
        $nextVersionNumber = $latestVersion ? $latestVersion->version_number + 1 : 1;

        $version = ResourceVersion::create([
            'resource_id' => $resource->id,
            'version_number' => $nextVersionNumber,
            'content_text' => null,
            'checksum' => $checksum,
            'change_note' => $changeNote,
            'created_by' => $uploader->id,
            'created_at' => now(),
        ]);

        $extension = $file->getClientOriginalExtension() ?: 'bin';
        $storagePath = "resources/{$checksum}.{$extension}";

        // Stockage sécurisé sur le disque privé
        Storage::disk('local')->putFileAs('private/resources', $file, "{$checksum}.{$extension}");

        $resourceFile = ResourceFile::create([
            'resource_version_id' => $version->id,
            'storage_key' => $storagePath,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType() ?: 'application/octet-stream',
            'size_bytes' => $file->getSize(),
            'checksum' => $checksum,
            'access_level' => 'private',
            'antivirus_scan_status' => 'clean',
            'is_quarantined' => false,
            'created_at' => now(),
        ]);

        AuditLog::record(
            $uploader->id,
            'DOCUMENT_INGESTED',
            'ResourceFile',
            $resourceFile->id,
            [
                'filename' => $resourceFile->original_name,
                'checksum' => $checksum,
                'size_bytes' => $resourceFile->size_bytes,
            ]
        );

        return $resourceFile;
    }

    /**
     * Extract pages and chunks from raw document text for pedagogical indexing.
     */
    public function extractPagesAndChunks(
        ResourceVersion $version,
        string $rawText,
        int $wordsPerPage = 250,
        int $wordsPerChunk = 80
    ): array {
        $words = preg_split('/\s+/u', trim($rawText));
        if (empty($words)) {
            return [];
        }

        $pagesData = array_chunk($words, $wordsPerPage);
        $createdPages = [];
        $totalChunks = 0;

        foreach ($pagesData as $pageIndex => $pageWords) {
            $pageNumber = $pageIndex + 1;
            $pageText = implode(' ', $pageWords);

            $page = ResourcePage::create([
                'resource_version_id' => $version->id,
                'page_number' => $pageNumber,
                'extracted_text' => $pageText,
                'extraction_status' => 'completed',
                'created_at' => now(),
            ]);

            // Découpage en chunks pour le RAG
            $chunkWordsData = array_chunk($pageWords, $wordsPerChunk);
            foreach ($chunkWordsData as $chunkIndexOnPage => $chunkWords) {
                $chunkText = implode(' ', $chunkWords);
                $tokenEstimate = (int) ceil(count($chunkWords) * 1.3);

                ResourceChunk::create([
                    'resource_version_id' => $version->id,
                    'page_id' => $page->id,
                    'chunk_index' => $totalChunks,
                    'content_text' => $chunkText,
                    'token_estimate' => $tokenEstimate,
                    'created_at' => now(),
                ]);

                $totalChunks++;
            }

            $createdPages[] = $page;
        }

        return [
            'pages_count' => count($createdPages),
            'chunks_count' => $totalChunks,
        ];
    }
}
