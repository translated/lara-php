<?php

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Complete document translation examples for the Lara PHP SDK
 *
 * This example demonstrates:
 * - Basic document translation
 * - Advanced options with memories and glossaries
 * - Step-by-step document translation with status monitoring
 */

use Lara\LaraCredentials;
use Lara\Translator;
use Lara\LaraException;
use Lara\DocumentUploadOptions;
use Lara\DocumentDownloadOptions;
use Lara\DocumentTranslateOptions;

function main() {
    // All examples use environment variables for credentials:
    // export LARA_ACCESS_KEY_ID="your-access-key-id"
    // export LARA_ACCESS_KEY_SECRET="your-access-key-secret"

    // Set your credentials here
    $accessKeyId = getenv('LARA_ACCESS_KEY_ID');
    $accessKeySecret = getenv('LARA_ACCESS_KEY_SECRET');

    $credentials = new LaraCredentials($accessKeyId, $accessKeySecret);
    $lara = new Translator($credentials);

    // Replace with your actual document file path
    $sampleFilePath = __DIR__ . '/sample_document.docx';  // Create this file with your content
    
    if (!file_exists($sampleFilePath)) {
        echo "Please create a sample document file at: $sampleFilePath\n";
        echo "Add some sample text content to translate.\n\n";
        return;
    }

    // Example 1: Basic document translation
    echo "=== Basic Document Translation ===\n";
    $sourceLang = "en-US";
    $targetLang = "de-DE";
    
    echo "Translating document: " . basename($sampleFilePath) . " from $sourceLang to $targetLang\n";
    
    try {
        $fileStream = $lara->documents->translate($sampleFilePath, $sourceLang, $targetLang);
        
        // Save translated document - replace with your desired output path
        $outputPath = __DIR__ . '/sample_document_translated.docx';
        $outputFile = fopen($outputPath, 'w');
        if ($outputFile) {
            while (!feof($fileStream)) {
                fwrite($outputFile, fread($fileStream, 8192));
            }
            fclose($outputFile);
            fclose($fileStream);
        }
        
        echo "✅ Document translation completed\n";
        echo "📄 Translated file saved to: " . basename($outputPath) . "\n\n";
    } catch (LaraException $e) {
        echo "Error translating document: " . $e->getMessage() . "\n";
    }

    // Example 2: Document translation with advanced options
    echo "=== Document Translation with Advanced Options ===\n";
    try {
        $translationOptions = new DocumentTranslateOptions();
        $translationOptions->setAdaptTo(["mem_1A2b3C4d5E6f7G8h9I0jKl"]);  // Replace with actual memory IDs
        $translationOptions->setGlossaries(["gls_1A2b3C4d5E6f7G8h9I0jKl"]);  // Replace with actual glossary IDs
        
        $fileStream = $lara->documents->translate($sampleFilePath, $sourceLang, $targetLang, $translationOptions);
        
        // Save translated document - replace with your desired output path
        $outputPath = __DIR__ . '/advanced_document_translated.docx';
        $outputFile = fopen($outputPath, 'w');
        if ($outputFile) {
            while (!feof($fileStream)) {
                fwrite($outputFile, fread($fileStream, 8192));
            }
            fclose($outputFile);
            fclose($fileStream);
        }
        
        echo "✅ Advanced document translation completed\n";
        echo "📄 Translated file saved to: " . basename($outputPath) . "\n";
    } catch (LaraException $e) {
        echo "Error in advanced translation: " . $e->getMessage() . "\n";
    }

    // Example 3: Step-by-step document translation with status monitoring
    echo "=== Step-by-Step Document Translation ===\n";
    
    try {
        // Upload document
        echo "Step 1: Uploading document...\n";
        $uploadOptions = new DocumentUploadOptions();
        $uploadOptions->setAdaptTo(["mem_1A2b3C4d5E6f7G8h9I0jKl"]);  // Replace with actual memory IDs if needed
        $uploadOptions->setGlossaries(["gls_1A2b3C4d5E6f7G8h9I0jKl"]);  // Replace with actual glossary IDs if needed
        
        $document = $lara->documents->upload($sampleFilePath, $sourceLang, $targetLang, $uploadOptions);
        echo "Document uploaded with ID: " . $document->getId() . "\n";
        echo "Initial status: " . $document->getStatus() . "\n";
        
        // Check status
        echo "\nStep 2: Checking status...\n";
        $updatedDocument = $lara->documents->status($document->getId());
        echo "Current status: " . $updatedDocument->getStatus() . "\n";
        
        // Download translated document
        echo "\nStep 3: Downloading would happen after translation completes...\n";
        $downloadOptions = new DocumentDownloadOptions();

        echo "✅ Step-by-step translation completed\n";
    } catch (LaraException $e) {
        echo "Error in step-by-step process: " . $e->getMessage() . "\n";
    }
}

main();
