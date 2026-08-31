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
 * - TMX import with a callback URL (async notification)
 * - Async memory export with callback URL
 * - Translation deletion
 * - Translation with TUID and context
 * - Sharing a memory with the account or a group (add, rename, list, revoke)
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

    // Example 5: TMX import with a callback URL (async notification when import completes)
    echo "=== TMX Import with Callback URL ===\n";
    if (file_exists($tmxFilePath)) {
        try {
            $callbackUrl = "https://your-server.example.com/lara/import-callback";  // Replace with your endpoint
            $tmxImportWithCallback = $lara->memories->importTmx($memoryId, $tmxFilePath, false, $callbackUrl);
            echo "Import started with ID: " . $tmxImportWithCallback->getId() . " (callback: $callbackUrl)\n";

            // You can also combine gzip + callbackUrl:
            // $lara->memories->importTmx($memoryId, $tmxFilePath, true, $callbackUrl);
            echo "\n";
        } catch (LaraException $e) {
            echo "Error starting TMX import with callback: " . $e->getMessage() . "\n\n";
        }
    } else {
        echo "TMX file not found: $tmxFilePath\n\n";
    }

    // Example 6: Async memory export
    // Starts an export job; the result is delivered asynchronously to the provided callback URL.
    echo "=== Async Memory Export ===\n";
    try {
        $exportCallbackUrl = "https://your-server.example.com/lara/export-callback";  // Replace with your endpoint
        $exportJob = $lara->memories->exportAsync($memoryId, $exportCallbackUrl, "tmx");
        echo "✅ Export job started (Job ID: " . $exportJob->getJobId() . ")\n";
        echo "The export result will be delivered to: $exportCallbackUrl\n\n";
    } catch (LaraException $e) {
        echo "Error starting async export: " . $e->getMessage() . "\n\n";
    }

    // Example 7: Translation deletion
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

    // Example 8: Memory sharing
    // Sharing requires a multi-user account and the appropriate role (account owner for
    // account-wide shares, owner/admin for group shares). Each call returns the shared
    // memory, whose name reflects the shared copy's name and sharedAt the share time.
    echo "=== Memory Sharing ===\n";
    try {
        // Share with the whole account/team (the optional second argument names the shared copy)
        $teamShare = $lara->memories->addAccountShare($memoryId, "Shared with the team");
        echo "🤝 Shared with the account as: '" . $teamShare->getName() . "' (shared at " . $teamShare->getSharedAt() . ")\n";

        // Rename the account/team share
        $renamedTeamShare = $lara->memories->renameAccountShare($memoryId, "Team memory");
        echo "📝 Renamed account share to: '" . $renamedTeamShare->getName() . "'\n";

        // List every share visible to the caller: the account share, group shares and user shares
        $shares = $lara->memories->getShares($memoryId);
        if ($shares->getAccount() !== null) {
            echo "👥 Account share '" . $shares->getAccount()->getShareName() . "' (" . $shares->getAccount()->getPermissions() . ")\n";
        }
        foreach ($shares->getGroups() as $group) {
            echo "👥 Group " . $group->getName() . ": '" . $group->getShareName() . "' (" . $group->getPermissions() . ")\n";
        }
        foreach ($shares->getUsers() as $user) {
            echo "👤 User " . $user->getName() . ": '" . $user->getShareName() . "' (" . $user->getPermissions() . ")\n";
        }

        // Revoke the account/team share
        $lara->memories->revokeAccountShare($memoryId);
        echo "🚫 Revoked the account share\n";

        // Group shares work the same way, addressed by a group ID (grp_...)
        $groupId = getenv("LARA_GROUP_ID");  // Replace with an actual group ID
        if ($groupId) {
            $groupShare = $lara->memories->addGroupShare($memoryId, $groupId, "Shared with the group");
            echo "🤝 Shared with group " . $groupId . " as: '" . $groupShare->getName() . "'\n";

            $lara->memories->renameGroupShare($memoryId, $groupId, "Marketing group");
            echo "📝 Renamed the group share\n";

            $lara->memories->revokeGroupShare($memoryId, $groupId);
            echo "🚫 Revoked the group share\n";
        } else {
            echo "Set LARA_GROUP_ID to try the group sharing methods.\n";
        }
        echo "\n";
    } catch (LaraException $e) {
        echo "Error sharing memory: " . $e->getMessage() . "\n\n";
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
