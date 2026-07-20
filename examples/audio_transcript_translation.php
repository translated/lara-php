<?php

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Audio transcript translation examples for the Lara PHP SDK
 *
 * This example demonstrates:
 * - One-shot transcript translation
 * - Step-by-step transcript translation with status monitoring
 */

use Lara\LaraCredentials;
use Lara\Translator;
use Lara\LaraException;
use Lara\AudioStatus;
use Lara\AudioTranscriptOptions;

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
        echo "Add some sample audio content to transcribe.\n\n";
        return;
    }

    $sourceLang = "en-US";
    $targetLang = "de-DE";

    // Example 1: One-shot transcript translation
    echo "=== Audio Transcript Translation ===\n";
    echo "Transcribing audio: " . basename($sampleFilePath) . " from $sourceLang to $targetLang\n";

    try {
        $result = $lara->audio->translateTranscript($sampleFilePath, $sourceLang, $targetLang);

        echo "Transcript translation completed\n";
        echo "Translation: " . $result->getTranslation() . "\n";
        echo "Segments: " . count($result->getSegments()) . "\n\n";
    } catch (LaraException $e) {
        echo "Error translating transcript: " . $e->getMessage() . "\n";
    }

    // Example 2: Step-by-step transcript translation with status monitoring
    echo "=== Step-by-Step Transcript Translation ===\n";

    try {
        // Upload audio
        echo "Step 1: Uploading audio...\n";
        $uploadOptions = new AudioTranscriptOptions();
        $uploadOptions->setAdaptTo(["mem_1A2b3C4d5E6f7G8h9I0jKl"]);  // Replace with actual memory IDs if needed
        $uploadOptions->setGlossaries(["gls_1A2b3C4d5E6f7G8h9I0jKl"]);  // Replace with actual glossary IDs if needed

        $audio = $lara->audio->uploadForTranscription($sampleFilePath, $sourceLang, $targetLang, $uploadOptions);
        echo "Audio uploaded with ID: " . $audio->getId() . "\n";
        echo "Initial status: " . $audio->getStatus() . "\n";

        // Poll status
        echo "\nStep 2: Waiting for the transcript to be translated...\n";
        $maxWaitTime = 900; // 15 minutes
        $startTime = time();
        while ($audio->getStatus() !== AudioStatus::TRANSLATED && $audio->getStatus() !== AudioStatus::ERROR) {
            if (time() - $startTime >= $maxWaitTime) {
                echo "Timed out waiting for the transcript translation\n";
                return;
            }

            sleep(2);

            $audio = $lara->audio->status($audio->getId());
            echo "Current status: " . $audio->getStatus() . "\n";
        }

        if ($audio->getStatus() === AudioStatus::ERROR) {
            echo "Transcript translation failed: " . $audio->getErrorReason() . "\n";
            return;
        }

        // Retrieve the translated transcript
        echo "\nStep 3: Retrieving the translated transcript...\n";
        $result = $lara->audio->getTranslatedTranscript($audio->getId());

        echo "Text: " . $result->getText() . "\n";
        echo "Translation: " . $result->getTranslation() . "\n";
        foreach ($result->getSegments() as $segment) {
            echo "[" . $segment->getStart() . " - " . $segment->getEnd() . "] " . $segment->getTranslation() . "\n";
        }

        echo "Step-by-step transcript translation completed\n";
    } catch (LaraException $e) {
        echo "Error in step-by-step process: " . $e->getMessage() . "\n";
    }
}

main();
