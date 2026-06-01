<?php

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Complete styleguide management examples for the Lara PHP SDK
 *
 * This example demonstrates:
 * - Create, list, get, update, delete styleguides
 * - Update name, content, or both at once
 * - Handling of non-existent styleguides
 */

use Lara\LaraCredentials;
use Lara\Translator;
use Lara\LaraException;

function main() {
    // All examples use environment variables for credentials, so set them first:
    // export LARA_ACCESS_KEY_ID="your-access-key-id"
    // export LARA_ACCESS_KEY_SECRET="your-access-key-secret"

    // Get credentials from environment variables
    $accessKeyId = getenv('LARA_ACCESS_KEY_ID');
    $accessKeySecret = getenv('LARA_ACCESS_KEY_SECRET');

    $credentials = new LaraCredentials($accessKeyId, $accessKeySecret);
    $lara = new Translator($credentials);

    echo "📋 Styleguides require a specific subscription plan.\n";
    echo "   If you encounter errors, please check your subscription level.\n\n";

    // Example 1: Basic styleguide management
    echo "=== Basic Styleguide Management ===\n";
    try {
        $styleguide = $lara->styleguides->create("MyDemoStyleguide", "Use a formal tone. Prefer British English spelling. Avoid contractions.");
        echo "✅ Created styleguide: " . $styleguide->getName() . " (ID: " . $styleguide->getId() . ")\n";

        // List all styleguides
        $styleguides = $lara->styleguides->getAll();
        echo "📝 Total styleguides: " . count($styleguides) . "\n";
        echo "\n";

        // Store the styleguide ID for later examples
        $styleguideId = $styleguide->getId();
    } catch (LaraException $e) {
        echo "Error creating styleguide: " . $e->getMessage() . "\n\n";
        return;
    }

    // Example 2: Styleguide operations
    echo "=== Styleguide Operations ===\n";
    try {
        // Get styleguide details
        $retrievedStyleguide = $lara->styleguides->get($styleguideId);
        if ($retrievedStyleguide) {
            echo "📖 Styleguide: " . $retrievedStyleguide->getName() . " (Owner: " . $retrievedStyleguide->getOwnerId() . ")\n";
            echo "📄 Content: " . $retrievedStyleguide->getContent() . "\n";
        }
        echo "\n";
    } catch (LaraException $e) {
        echo "Error with styleguide operations: " . $e->getMessage() . "\n\n";
    }

    // Example 3: Update styleguide
    echo "=== Update Styleguide ===\n";
    try {
        // Update only the name
        $renamedStyleguide = $lara->styleguides->update($styleguideId, "UpdatedDemoStyleguide");
        echo "📝 Updated name: '" . $styleguide->getName() . "' -> '" . $renamedStyleguide->getName() . "'\n";

        // Update only the content
        $updatedStyleguide = $lara->styleguides->update($styleguideId, null, "Use a casual tone. Prefer American English spelling. Contractions are welcome.");
        echo "📝 Updated content for styleguide: " . $updatedStyleguide->getName() . "\n";

        // Update both name and content
        $fullyUpdated = $lara->styleguides->update($styleguideId, "FinalDemoStyleguide", "Use clear and concise language. Avoid jargon.");
        echo "📝 Updated name and content: " . $fullyUpdated->getName() . "\n";
        echo "\n";
    } catch (LaraException $e) {
        echo "Error updating styleguide: " . $e->getMessage() . "\n\n";
    }

    // Example 4: Get a non-existent styleguide
    echo "=== Get Non-Existent Styleguide ===\n";
    try {
        $missing = $lara->styleguides->get("non-existent-id");
        if ($missing === null) {
            echo "ℹ️  Styleguide not found (returned null as expected)\n";
        }
        echo "\n";
    } catch (LaraException $e) {
        echo "Error getting styleguide: " . $e->getMessage() . "\n\n";
    }

    // Cleanup
    echo "=== Cleanup ===\n";
    try {
        $deletedStyleguide = $lara->styleguides->delete($styleguideId);
        echo "🗑️  Deleted styleguide: " . $deletedStyleguide->getName() . "\n";
    } catch (LaraException $e) {
        echo "Error deleting styleguide: " . $e->getMessage() . "\n";
    }

    echo "\n🎉 Styleguide management examples completed!\n";
}

main();
