<?php

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Complete memory management examples for the Lara PHP SDK
 *
 * This example demonstrates:
 * - Create, list, update, delete memories
 * - Add individual translations
 * - Multiple memory operations
 * - TMX file import with progress monitoring
 * - Translation deletion
 * - Translation with TUID and context
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

    // Example 1: Basic memory management
    echo "=== Basic Memory Management ===\n";
    try {
        $memory = $lara->memories->create("MyDemoMemory");
        echo "✅ Created memory: " . $memory->getName() . " (ID: " . $memory->getId() . ")\n";

        // Get memory details
        $retrievedMemory = $lara->memories->get($memory->getId());
        if ($retrievedMemory) {
            echo "📖 Memory: " . $retrievedMemory->getName() . " (Owner: " . $retrievedMemory->getOwnerId() . ")\n";
        }

        // Update memory
        $updatedMemory = $lara->memories->update($memory->getId(), "UpdatedDemoMemory");
        echo "📝 Updated name: '" . $memory->getName() . "' -> '" . $updatedMemory->getName() . "'\n";
        echo "\n";

        // List all memories
        $memories = $lara->memories->getAll();
        echo "📝 Total memories: " . count($memories) . "\n";
        echo "\n";

        // Store the memory ID for later examples
        $memoryId = $memory->getId();
    } catch (LaraException $e) {
        echo "Error creating memory: " . $e->getMessage() . "\n\n";
        return;
    }

    // Example 2: Adding translations
    // Important: To update/overwrite a translation unit you must provide a tuid. Calls without a tuid always create a new unit and will not update existing entries.
    echo "=== Adding Translations ===\n";
    try {
        // Basic translation addition (with TUID)
        $memImport1 = $lara->memories->addTranslation($memoryId, "en-US", "fr-FR", "Hello", "Bonjour", "greeting_001");
        echo "✅ Added: 'Hello' -> 'Bonjour' with TUID 'greeting_001' (Import ID: " . $memImport1->getId() . ")\n";

        // Translation with context
        $memImport2 = $lara->memories->addTranslation(
            $memoryId, "en-US", "fr-FR", "How are you?", "Comment allez-vous?", "greeting_002",
            "Good morning", "Have a nice day"
        );
        echo "✅ Added with context (Import ID: " . $memImport2->getId() . ")\n";
        echo "\n";
    } catch (LaraException $e) {
        echo "Error adding translations: " . $e->getMessage() . "\n\n";
    }

    // Example 3: Multiple memory operations
    echo "=== Multiple Memory Operations ===\n";
    try {
        // Create second memory for multi-memory operations
        $memory2 = $lara->memories->create("SecondDemoMemory");
        $memory2Id = $memory2->getId();
        echo "✅ Created second memory: " . $memory2->getName() . "\n";

        // Add translation to multiple memories (with TUID)
        $memoryIds = [$memoryId, $memory2Id];
        $multiImportJob = $lara->memories->addTranslation($memoryIds, "en-US", "it-IT", "Hello World!", "Ciao Mondo!", "greeting_003");
        echo "✅ Added translation to multiple memories (Import ID: " . $multiImportJob->getId() . ")\n";
        echo "\n";

        // Store for cleanup
        $memory2ToDelete = $memory2Id;
    } catch (LaraException $e) {
        echo "Error with multiple memory operations: " . $e->getMessage() . "\n\n";
        $memory2ToDelete = null;
    }

    // Example 4: TMX import functionality
    echo "=== TMX Import Functionality ===\n";

    // Replace with your actual TMX file path
    $tmxFilePath = __DIR__ . '/sample_memory.tmx';  // Create this file with your TMX content

    if (file_exists($tmxFilePath)) {
        try {
            echo "Importing TMX file: " . basename($tmxFilePath) . "\n";
            $tmxImport = $lara->memories->importTmx($memoryId, $tmxFilePath);
            echo "Import started with ID: " . $tmxImport->getId() . "\n";
            echo "Initial progress: " . ($tmxImport->getProgress() * 100) . "%\n";

            // Wait for import to complete
            try {
                $completedImport = $lara->memories->waitForImport($tmxImport, 10);
                echo "✅ Import completed!\n";
                echo "Final progress: " . ($completedImport->getProgress() * 100) . "%\n";
            } catch (LaraTimeoutException $e) {
                echo "Import timeout: The import process took too long to complete.\n";
            }
            echo "\n";
        } catch (LaraException $e) {
            echo "Error with TMX import: " . $e->getMessage() . "\n\n";
        }
    } else {
        echo "TMX file not found: $tmxFilePath\n";
    }

    // Example 5: Translation deletion
    echo "=== Translation Deletion ===\n";
    try {
        // Delete a specific translation unit (with TUID)
        // Important: if you omit tuid, all entries that match the provided fields will be removed
        $deleteJob = $lara->memories->deleteTranslation(
            $memoryId,
            "en-US",
            "fr-FR",
            "Hello",
            "Bonjour",
            "greeting_001"  // Specify the TUID to delete a specific translation unit
        );
        echo "🗑️  Deleted translation unit (Job ID: " . $deleteJob->getId() . ")\n";
        echo "\n";
    } catch (LaraException $e) {
        echo "Error deleting translation: " . $e->getMessage() . "\n\n";
    }

    // Cleanup
    echo "=== Cleanup ===\n";
    try {
        $deletedMemory = $lara->memories->delete($memoryId);
        echo "🗑️  Deleted memory: " . $deletedMemory->getName() . "\n";

        if ($memory2ToDelete) {
            $deletedMemory2 = $lara->memories->delete($memory2ToDelete);
            echo "🗑️  Deleted second memory: " . $deletedMemory2->getName() . "\n";
        }
    } catch (LaraException $e) {
        echo "Error deleting memory: " . $e->getMessage() . "\n";
    }

    echo "\n🎉 Memory management examples completed!\n";
}

main();