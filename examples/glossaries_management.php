<?php

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Complete glossary management examples for the Lara PHP SDK
 *
 * This example demonstrates:
 * - Create, list, update, delete glossaries
 * - CSV import with status monitoring
 * - Glossary export (sync and async)
 * - Glossary terms count
 * - Import status checking
 */

use Lara\LaraCredentials;
use Lara\Translator;
use Lara\LaraException;
use Lara\LaraTimeoutException;

function main() {
    // All examples use environment variables for credentials, so set them first:
    // export LARA_ACCESS_KEY_ID="your-access-key-id"
    // export LARA_ACCESS_KEY_SECRET="your-access-key-secret"

    // Get credentials from environment variables
    $accessKeyId = getenv('LARA_ACCESS_KEY_ID');
    $accessKeySecret = getenv('LARA_ACCESS_KEY_SECRET');

    $credentials = new LaraCredentials($accessKeyId, $accessKeySecret);
    $lara = new Translator($credentials);

    echo "🗒️  Glossaries require a specific subscription plan.\n";
    echo "   If you encounter errors, please check your subscription level.\n\n";

    // Example 1: Basic glossary management
    echo "=== Basic Glossary Management ===\n";
    try {
        $glossary = $lara->glossaries->create("MyDemoGlossary");
        echo "✅ Created glossary: " . $glossary->getName() . " (ID: " . $glossary->getId() . ")\n";

        // List all glossaries
        $glossaries = $lara->glossaries->getAll();
        echo "📝 Total glossaries: " . count($glossaries) . "\n";
        echo "\n";

        // Store the glossary ID for later examples
        $glossaryId = $glossary->getId();
    } catch (LaraException $e) {
        echo "Error creating glossary: " . $e->getMessage() . "\n\n";
        return;
    }

    // Example 2: Glossary operations
    echo "=== Glossary Operations ===\n";
    try {
        // Get glossary details
        $retrievedGlossary = $lara->glossaries->get($glossaryId);
        if ($retrievedGlossary) {
            echo "📖 Glossary: " . $retrievedGlossary->getName() . " (Owner: " . $retrievedGlossary->getOwnerId() . ")\n";
        }

        // Get glossary terms count
        $counts = $lara->glossaries->counts($glossaryId);
        if ($counts->getUnidirectional()) {
            foreach ($counts->getUnidirectional() as $lang => $count) {
                echo "   $lang: $count entries\n";
            }
        }

        // Update glossary
        $updatedGlossary = $lara->glossaries->update($glossaryId, "UpdatedDemoGlossary");
        echo "📝 Updated name: '" . $glossary->getName() . "' -> '" . $updatedGlossary->getName() . "'\n";
        echo "\n";
    } catch (LaraException $e) {
        echo "Error with glossary operations: " . $e->getMessage() . "\n\n";
    }

    // Example 3: CSV import functionality
    echo "=== CSV Import Functionality ===\n";

    // Replace with your actual CSV file path
    $csvFilePath = __DIR__ . '/sample_glossary.csv';  // Create this file with your glossary data

    if (file_exists($csvFilePath)) {
        try {
        echo "Importing CSV file: " . basename($csvFilePath) . "\n";
        $import = $lara->glossaries->importCsv($glossaryId, $csvFilePath);
        echo "Import started with ID: " . $import->getId() . "\n";
        echo "Initial progress: " . ($import->getProgress() * 100) . "%\n";

        // Check import status manually
        echo "Checking import status...\n";
        $importStatus = $lara->glossaries->getImportStatus($import->getId());
        echo "Current progress: " . ($importStatus->getProgress() * 100) . "%\n";

        // Wait for import to complete
        try {
            $completedImport = $lara->glossaries->waitForImport($import, 10);
            echo "✅ Import completed!\n";
            echo "Final progress: " . ($completedImport->getProgress() * 100) . "%\n";
        } catch (LaraTimeoutException $e) {
            echo "Import timeout: The import process took too long to complete.\n";
        }
            echo "\n";
        } catch (LaraException $e) {
            echo "Error with CSV import: " . $e->getMessage() . "\n\n";
        }
    } else {
        echo "CSV file not found: $csvFilePath\n";
    }

    // Example 4: CSV import with a callback URL (async notification when import completes)
    echo "=== CSV Import with Callback URL ===\n";
    if (file_exists($csvFilePath)) {
        try {
            $callbackUrl = "https://your-server.example.com/lara/import-callback";  // Replace with your endpoint
            // Note: the callback URL must follow the gzip flag (importCsv has no callback-only overload),
            // so pass gzip explicitly even when you don't need compression.
            $importWithCallback = $lara->glossaries->importCsv($glossaryId, $csvFilePath, false, $callbackUrl);
            echo "Import started with ID: " . $importWithCallback->getId() . " (callback: $callbackUrl)\n";

            // You can also combine a content type + gzip + callbackUrl:
            // $lara->glossaries->importCsvWithContentType($glossaryId, $csvFilePath, "csv/table-uni", true, $callbackUrl);
            echo "\n";
        } catch (LaraException $e) {
            echo "Error starting CSV import with callback: " . $e->getMessage() . "\n\n";
        }
    } else {
        echo "CSV file not found: $csvFilePath\n\n";
    }

    // Example 5: Export functionality
    echo "=== Export Functionality ===\n";
    try {
        // Export as CSV table unidirectional format
        echo "📤 Exporting as CSV table unidirectional...\n";
        $csvUniData = $lara->glossaries->export($glossaryId, "csv/table-uni", "en-US");
        echo "✅ CSV unidirectional export successful (" . strlen($csvUniData) . " bytes)\n";

        // Save sample exports to files - replace with your desired output paths
        $exportFilePath = __DIR__ . '/exported_glossary.csv';  // Replace with actual path
        file_put_contents($exportFilePath, $csvUniData);
        echo "💾 Sample export saved to: " . basename($exportFilePath) . "\n";

        // Async export - returns a job ID; the result is delivered to your callback URL when ready
        echo "📤 Starting async export...\n";
        $exportJob = $lara->glossaries->exportAsync(
            $glossaryId,
            "https://your-server.example.com/lara/export-callback",  // Replace with your actual callback URL
            "csv/table-uni",
            "en-US"
        );
        echo "✅ Async export started (Job ID: " . $exportJob->getJobId() . ")\n";
        echo "   The export result will be delivered to your callback URL when ready.\n";
        echo "\n";
    } catch (LaraException $e) {
        echo "Error with export: " . $e->getMessage() . "\n\n";
    }

    // Example 6: Glossary Terms Count
    echo "=== Glossary Terms Count ===\n";
    try {
        // Get detailed counts
        $counts = $lara->glossaries->counts($glossaryId);

        echo "📊 Detailed glossary terms count:\n";

        if ($counts->getUnidirectional()) {
            echo "   Unidirectional entries by language pair:\n";
            foreach ($counts->getUnidirectional() as $langPair => $count) {
                echo "     $langPair: $count terms\n";
            }
        } else {
            echo "   No unidirectional entries found\n";
        }
        
        $totalEntries = 0;
        if ($counts->getUnidirectional()) {
            $totalEntries += array_sum($counts->getUnidirectional());
        }
        echo "   Total entries: $totalEntries\n";
        echo "\n";
    } catch (LaraException $e) {
        echo "Error getting glossary terms count: " . $e->getMessage() . "\n\n";
    }

    // Cleanup
    echo "=== Cleanup ===\n";
    try {
        $deletedGlossary = $lara->glossaries->delete($glossaryId);
        echo "🗑️  Deleted glossary: " . $deletedGlossary->getName() . "\n";

        // Clean up export files - replace with actual cleanup if needed
        $exportFilePath = __DIR__ . '/exported_glossary.csv';
        if (file_exists($exportFilePath)) {
            unlink($exportFilePath);
            echo "🗑️  Cleaned up export file\n";
        }
    } catch (LaraException $e) {
        echo "Error deleting glossary: " . $e->getMessage() . "\n";
    }

    echo "\n🎉 Glossary management examples completed!\n";
}

main();