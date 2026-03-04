<?php

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Complete audio translation examples for the Lara PHP SDK
 *
 * This example demonstrates:
 * - Basic audio translation
 * - Advanced options with memories, glossaries, and instructions
 * - Step-by-step audio translation with status monitoring
 */

use Lara\LaraCredentials;
use Lara\Translator;
use Lara\LaraException;
use Lara\AudioTranslateOptions;

function main() {
    // All examples use environment variables for credentials:
    // export LARA_ACCESS_KEY_ID="your-access-key-id"
    // export LARA_ACCESS_KEY_SECRET="your-access-key-secret"

    // Set your credentials here
    $accessKeyId = getenv('LARA_ACCESS_KEY_ID');
    $accessKeySecret = getenv('LARA_ACCESS_KEY_SECRET');

    $credentials = new LaraCredentials($accessKeyId, $accessKeySecret);
    $lara = new Translator($credentials);

    // Replace with your actual audio file path
    $sampleFilePath = __DIR__ . '/sample_audio.mp3';  // Create this file with your content

    if (!file_exists($sampleFilePath)) {
        echo "Please create a sample audio file at: $sampleFilePath\n";
        echo "Add some sample audio content to translate.\n\n";
        return;
    }

    // Example 1: Basic audio translation
    echo "=== Basic Audio Translation ===\n";
    $sourceLang = "en-US";
    $targetLang = "de-DE";

    echo "Translating audio: " . basename($sampleFilePath) . " from $sourceLang to $targetLang\n";

    try {
        $fileStream = $lara->audio->translate($sampleFilePath, $sourceLang, $targetLang);

        // Save translated audio - replace with your desired output path
        $outputPath = __DIR__ . '/sample_audio_translated.mp3';
        $outputFile = fopen($outputPath, 'w');
        if ($outputFile) {
            while (!feof($fileStream)) {
                fwrite($outputFile, fread($fileStream, 8192));
            }
            fclose($outputFile);
            fclose($fileStream);
        }

        echo "Audio translation completed\n";
        echo "Translated file saved to: " . basename($outputPath) . "\n\n";
    } catch (LaraException $e) {
        echo "Error translating audio: " . $e->getMessage() . "\n";
    }

    // Example 2: Audio translation with advanced options
    echo "=== Audio Translation with Advanced Options ===\n";
    try {
        $translationOptions = new AudioTranslateOptions();
        $translationOptions->setAdaptTo(["mem_1A2b3C4d5E6f7G8h9I0jKl"]);  // Replace with actual memory IDs
        $translationOptions->setGlossaries(["gls_1A2b3C4d5E6f7G8h9I0jKl"]);  // Replace with actual glossary IDs

        $fileStream = $lara->audio->translate($sampleFilePath, $sourceLang, $targetLang, $translationOptions);

        // Save translated audio - replace with your desired output path
        $outputPath = __DIR__ . '/advanced_audio_translated.mp3';
        $outputFile = fopen($outputPath, 'w');
        if ($outputFile) {
            while (!feof($fileStream)) {
                fwrite($outputFile, fread($fileStream, 8192));
            }
            fclose($outputFile);
            fclose($fileStream);
        }

        echo "Advanced audio translation completed\n";
        echo "Translated file saved to: " . basename($outputPath) . "\n";
    } catch (LaraException $e) {
        echo "Error in advanced translation: " . $e->getMessage() . "\n";
    }

    // Example 3: Step-by-step audio translation with status monitoring
    echo "=== Step-by-Step Audio Translation ===\n";

    try {
        // Upload audio
        echo "Step 1: Uploading audio...\n";
        $uploadOptions = new AudioTranslateOptions();
        $uploadOptions->setAdaptTo(["mem_1A2b3C4d5E6f7G8h9I0jKl"]);  // Replace with actual memory IDs if needed
        $uploadOptions->setGlossaries(["gls_1A2b3C4d5E6f7G8h9I0jKl"]);  // Replace with actual glossary IDs if needed

        $audio = $lara->audio->upload($sampleFilePath, $sourceLang, $targetLang, $uploadOptions);
        echo "Audio uploaded with ID: " . $audio->getId() . "\n";
        echo "Initial status: " . $audio->getStatus() . "\n";

        // Check status
        echo "\nStep 2: Checking status...\n";
        $updatedAudio = $lara->audio->status($audio->getId());
        echo "Current status: " . $updatedAudio->getStatus() . "\n";
        if ($updatedAudio->getTotalSeconds()) {
            echo "Progress: " . $updatedAudio->getTranslatedSeconds() . "/" . $updatedAudio->getTotalSeconds() . " seconds\n";
        }

        // Download translated audio
        echo "\nStep 3: Downloading would happen after translation completes...\n";

        echo "Step-by-step translation completed\n";
    } catch (LaraException $e) {
        echo "Error in step-by-step process: " . $e->getMessage() . "\n";
    }
}

main();
