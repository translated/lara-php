<?php

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Complete text translation examples for the Lara PHP SDK
 *
 * This example demonstrates:
 * - Single string translation
 * - Multiple strings translation
 * - Translation with instructions
 * - TextBlocks translation (mixed translatable/non-translatable content)
 * - Auto-detect source language
 * - Advanced translation options
 * - Get available languages
 */

use Lara\LaraCredentials;
use Lara\Translator;
use Lara\LaraException;
use Lara\TranslateOptions;
use Lara\TextBlock;

function main() {
    // All examples use environment variables for credentials, so set them first:
    // export LARA_ACCESS_KEY_ID="your-access-key-id"
    // export LARA_ACCESS_KEY_SECRET="your-access-key-secret"

    // Get credentials from environment variables
    $accessKeyId = getenv('LARA_ACCESS_KEY_ID');
    $accessKeySecret = getenv('LARA_ACCESS_KEY_SECRET');

    $credentials = new LaraCredentials($accessKeyId, $accessKeySecret);
    $lara = new Translator($credentials);
    
    // Example 1: Basic single string translation
    echo "=== Basic Single String Translation ===\n";
    try {
        $result = $lara->translate("Hello, world!", "en-US", "fr-FR");
        echo "Original: Hello, world!\n";
        echo "French: " . $result->getTranslation() . "\n\n";
    } catch (LaraException $e) {
        echo "Error translating text: " . $e->getMessage() . "\n\n";
        return;
    }

    // Example 2: Multiple strings translation
    echo "=== Multiple Strings Translation ===\n";
    try {
        $result = $lara->translate(["Hello", "How are you?", "Goodbye"], "en-US", "es-ES");
        echo "Original: [Hello, How are you?, Goodbye]\n";
        echo "Spanish: [" . implode(", ", $result->getTranslation()) . "]\n\n";
    } catch (LaraException $e) {
        echo "Error translating multiple texts: " . $e->getMessage() . "\n\n";
        return;
    }

    // Example 3: TextBlocks translation (mixed translatable/non-translatable content)
    echo "=== TextBlocks Translation ===\n";
    try {
        $textBlocks = [
            new TextBlock('Adventure novels, mysteries, cookbooks—wait, who packed those?', true),
            new TextBlock('<br>', false),  // Non-translatable HTML
            new TextBlock('Suddenly, it doesn\'t feel so deserted after all.', true),
            new TextBlock('<div class="separator"></div>', false),  // Non-translatable HTML
            new TextBlock('Every page you turn is a new journey, and the best part?', true),
        ];
        
        $result = $lara->translate($textBlocks, "en-US", "it-IT");
        $translations = $result->getTranslation();
        
        echo "Original TextBlocks: " . count($textBlocks) . " blocks\n";
        echo "Translated blocks: " . count($translations) . "\n";
        foreach ($translations as $i => $translation) {
            echo "Block " . ($i + 1) . ": " . $translation . "\n";
        }
        echo "\n";
    } catch (LaraException $e) {
        echo "Error with TextBlocks translation: " . $e->getMessage() . "\n\n";
        return;
    }

    // Example 4: Translation with instructions
    echo "=== Translation with Instructions ===\n";
    try {
        $options = new TranslateOptions([
            'instructions' => ["Be formal", "Use technical terminology"]
        ]);
        
        $result = $lara->translate("Could you send me the report by tomorrow morning?", "en-US", "de-DE", $options);
        echo "Original: Could you send me the report by tomorrow morning?\n";
        echo "German (formal): " . $result->getTranslation() . "\n\n";
    } catch (LaraException $e) {
        echo "Error with instructed translation: " . $e->getMessage() . "\n\n";
        return;
    }

    // Example 5: Auto-detecting source language
    echo "=== Auto-detect Source Language ===\n";
    try {
        $result = $lara->translate("Bonjour le monde!", null, "en-US");
        echo "Original: Bonjour le monde!\n";
        echo "Detected source: " . $result->getSourceLanguage() . "\n";
        echo "English: " . $result->getTranslation() . "\n\n";
    } catch (LaraException $e) {
        echo "Error with auto-detection: " . $e->getMessage() . "\n\n";
        return;
    }

    // Example 6: Advanced options with comprehensive settings
    echo "=== Translation with Advanced Options ===\n";
    try {
        $options = new TranslateOptions([
            'adaptTo' => ["mem_1A2b3C4d5E6f7G8h9I0jKl", "mem_2XyZ9AbC8dEf7GhI6jKlMn"],  // Replace with actual memory IDs
            'glossaries' => ["gls_1A2b3C4d5E6f7G8h9I0jKl", "gls_2XyZ9AbC8dEf7GhI6jKlMn"],  // Replace with actual glossary IDs
            'instructions' => ["Be professional"],
            'style' => 'fluid',
            'contentType' => 'text/plain',
            'timeoutInMillis' => 10000,
        ]);
        
        $result = $lara->translate("This is a comprehensive translation example", "en-US", "it-IT", $options);
        echo "Original: This is a comprehensive translation example\n";
        echo "Italian (with all options): " . $result->getTranslation() . "\n\n";
    } catch (LaraException $e) {
        echo "Error with advanced translation: " . $e->getMessage() . "\n\n";
        return;
    }

    // Example 7: Get available languages
    echo "=== Available Languages ===\n";
    try {
        $languages = $lara->getLanguages();
        echo "Supported languages: [" . implode(", ", $languages) . "]\n";
    } catch (LaraException $e) {
        echo "Error getting languages: " . $e->getMessage() . "\n";
        return;
    }

    // Example 8: Detect language of a given text
    echo "=== Detect language ===\n";
    try {
        $detect_result_1 = $lara->detect("Hello, world!");
        echo "Detected language for 'Hello, world!': " . $detect_result_1->getLanguage() . "\n";
    } catch (LaraException $e) {
        echo "Error detecting language: " . $e->getMessage() . "\n";
        return;
    }

    // Example 9: Detect language of a given text with hint and passlist
    echo "=== Detect language with hint and passlist ===\n";
    try {
        $detect_result_2 = $lara->detect("Hello, world!", "en", ["en", "fr"]);
        echo "Detected language for 'Hello, world!': " . $detect_result_2->getLanguage() . "\n";
    } catch (LaraException $e) {
        echo "Error detecting language: " . $e->getMessage() . "\n";
        return;
    }
}

main();