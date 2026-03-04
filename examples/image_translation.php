<?php

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Complete image translation examples for the Lara PHP SDK
 *
 * This example demonstrates:
 * - Basic image translation
 * - Advanced options with memories and glossaries
 * - Extracting and translating text from an image
 */

use Lara\LaraCredentials;
use Lara\Translator;
use Lara\LaraException;
use Lara\ImageTranslationOptions;
use Lara\ImageTextTranslationOptions;

function main() {
    // All examples use environment variables for credentials, so set them first:
    // export LARA_ACCESS_KEY_ID="your-access-key-id"
    // export LARA_ACCESS_KEY_SECRET="your-access-key-secret"

    $accessKeyId = getenv('LARA_ACCESS_KEY_ID');
    $accessKeySecret = getenv('LARA_ACCESS_KEY_SECRET');

    $credentials = new LaraCredentials($accessKeyId, $accessKeySecret);
    $lara = new Translator($credentials);

    // Replace with your actual image file path
    $sampleFilePath = __DIR__ . '/sample_image.png';

    if (!file_exists($sampleFilePath)) {
        echo "Please create a sample image file at: $sampleFilePath\n";
        return;
    }

    $sourceLang = "en";
    $targetLang = "de";

    // Example 1: Basic image translation (image output)
    echo "=== Basic Image Translation ===\n";
    echo "Translating image: " . basename($sampleFilePath) . " from $sourceLang to $targetLang\n";

    try {
        $translatedStream = $lara->images->translate($sampleFilePath, $sourceLang, $targetLang, new ImageTranslationOptions([
            'textRemoval' => 'overlay'
        ]));

        $outputPath = __DIR__ . '/sample_image_translated.png';
        $outputFile = fopen($outputPath, 'w');
        stream_copy_to_stream($translatedStream, $outputFile);
        fclose($outputFile);
        fclose($translatedStream);

        echo "Image translation completed\n";
        echo "Translated image saved to: " . basename($outputPath) . "\n\n";
    } catch (LaraException $e) {
        echo "Error translating image: " . $e->getMessage() . "\n\n";
        return;
    }

    // Example 2: Image translation with advanced options
    echo "=== Image Translation with Advanced Options ===\n";
    try {
        $translatedStream2 = $lara->images->translate($sampleFilePath, $sourceLang, $targetLang, new ImageTranslationOptions([
            'adaptTo' => ['mem_1A2b3C4d5E6f7G8h9I0jKl'],       // Replace with actual memory IDs
            'glossaries' => ['gls_1A2b3C4d5E6f7G8h9I0jKl'],    // Replace with actual glossary IDs
            'style' => 'faithful',
            'textRemoval' => 'inpainting'
        ]));

        $advancedOutputPath = __DIR__ . '/advanced_image_translated.png';
        $outputFile2 = fopen($advancedOutputPath, 'w');
        stream_copy_to_stream($translatedStream2, $outputFile2);
        fclose($outputFile2);
        fclose($translatedStream2);

        echo "Advanced image translation completed\n";
        echo "Translated image saved to: " . basename($advancedOutputPath) . "\n\n";
    } catch (LaraException $e) {
        echo "Error in advanced translation: " . $e->getMessage() . "\n\n";
    }

    // Example 3: Extract and translate text from an image
    echo "=== Extract and Translate Text ===\n";
    try {
        $results = $lara->images->translateText($sampleFilePath, $sourceLang, $targetLang, new ImageTextTranslationOptions([
            'adaptTo' => ['mem_1A2b3C4d5E6f7G8h9I0jKl'],       // Replace with actual memory IDs
            'glossaries' => ['gls_1A2b3C4d5E6f7G8h9I0jKl'],    // Replace with actual glossary IDs
            'style' => 'faithful'
        ]));

        echo "Extract and translate completed\n";
        echo "Found " . count($results) . " text blocks\n";

        foreach ($results as $index => $result) {
            echo "\nText Block " . ($index + 1) . ":\n";
            echo "Original: " . $result->getText() . "\n";
            echo "Translated: " . $result->getTranslation() . "\n";
        }
    } catch (LaraException $e) {
        echo "Error extracting and translating text: " . $e->getMessage() . "\n";
    }
}

main();
