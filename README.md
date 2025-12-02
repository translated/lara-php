# Lara PHP SDK

[![PHP Version](https://img.shields.io/badge/php-7.4+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

This SDK empowers you to build your own branded translation AI leveraging our translation fine-tuned language model. 

All major translation features are accessible, making it easy to integrate and customize for your needs. 

## 🌍 **Features:**
- **Text Translation**: Single strings, multiple strings, and complex text blocks
- **Document Translation**: Word, PDF, and other document formats with status monitoring
- **Translation Memory**: Store and reuse translations for consistency
- **Glossaries**: Enforce terminology standards across translations
- **Language Detection**: Automatic source language identification
- **Advanced Options**: Translation instructions and more

## 📚 Documentation

Lara's SDK full documentation is available at [https://developers.laratranslate.com/](https://developers.laratranslate.com/)

## 🚀 Quick Start

### Installation

```bash
composer require translated/lara-php
```

### Basic Usage

```php
require_once 'vendor/autoload.php';

use Lara\LaraCredentials;
use Lara\Translator;
use Lara\LaraException;

// Set your credentials using environment variables (recommended)
$credentials = new LaraCredentials(
    getenv('LARA_ACCESS_KEY_ID'),
    getenv('LARA_ACCESS_KEY_SECRET')
);

// Create translator instance
$lara = new Translator($credentials);

// Simple text translation
try {
    $result = $lara->translate("Hello, world!", "en-US", "fr-FR");
    echo "Translation: " . $result->getTranslation() . PHP_EOL;
    // Output: Translation: Bonjour, le monde !
} catch (LaraException $error) {
    echo "Translation error: " . $error->getMessage() . PHP_EOL;
}
```

## 📖 Examples

The `examples/` directory contains comprehensive examples for all SDK features.

**All examples use environment variables for credentials, so set them first:**
```bash
export LARA_ACCESS_KEY_ID="your-access-key-id"
export LARA_ACCESS_KEY_SECRET="your-access-key-secret"
```

### Text Translation
- **[text_translation.php](examples/text_translation.php)** - Complete text translation examples
  - Single string translation
  - Multiple strings translation  
  - Translation with instructions
  - TextBlocks translation (mixed translatable/non-translatable content)
  - Auto-detect source language
  - Advanced translation options
  - Get available languages
  - Detect language

```bash
cd examples
php text_translation.php
```

### Document Translation
- **[document_translation.php](examples/document_translation.php)** - Document translation examples
  - Basic document translation
  - Advanced options with memories and glossaries
  - Step-by-step translation with status monitoring

```bash
cd examples
php document_translation.php
```

### Translation Memory Management
- **[memories_management.php](examples/memories_management.php)** - Memory management examples
  - Create, list, update, delete memories
  - Add individual translations
  - Multiple memory operations
  - TMX file import with progress monitoring
  - Translation deletion
  - Translation with TUID and context

```bash
cd examples
php memories_management.php
```

### Glossary Management
- **[glossaries_management.php](examples/glossaries_management.php)** - Glossary management examples
  - Create, list, update, delete glossaries
  - CSV import with status monitoring
  - Glossary export
  - Glossary terms count
  - Import status checking

```bash
cd examples
php glossaries_management.php
```

## 🔧 API Reference

### Core Components

### 🔐 Authentication

The SDK supports authentication via access key and secret:

```php
$credentials = new LaraCredentials("your-access-key-id", "your-access-key-secret");
$lara = new Translator($credentials);
```

**Environment Variables (Recommended):**
```bash
export LARA_ACCESS_KEY_ID="your-access-key-id"
export LARA_ACCESS_KEY_SECRET="your-access-key-secret"
```

```php
$credentials = new LaraCredentials(
    getenv('LARA_ACCESS_KEY_ID'),
    getenv('LARA_ACCESS_KEY_SECRET')
);
```

### 🌍 Translator

```php
// Create translator with credentials
$lara = new Translator($credentials);
```

#### Text Translation

```php
// Basic translation
$result = $lara->translate("Hello", "en-US", "fr-FR");

// Multiple strings
$result = $lara->translate(["Hello", "World"], "en-US", "fr-FR");

// TextBlocks (mixed translatable/non-translatable content)
use Lara\TextBlock;

$textBlocks = [
    new TextBlock('Translatable text', true),
    new TextBlock('<br>', false),  // Non-translatable HTML
    new TextBlock('More translatable text', true),
];
$result = $lara->translate($textBlocks, "en-US", "fr-FR");

// With advanced options  
$options = new TranslateOptions([
    'instructions' => ["Formal tone"],
    'adaptTo' => ["mem_1A2b3C4d5E6f7G8h9I0jKl"],  // Replace with actual memory IDs
    'glossaries' => ["gls_1A2b3C4d5E6f7G8h9I0jKl"],  // Replace with actual glossary IDs
    'style' => "fluid",
    'timeoutInMillis' => 10000
]);

$result = $lara->translate("Hello", "en-US", "fr-FR", $options);
```

### 📖 Document Translation
#### Simple document translation

```php
$filePath = "/path/to/your/document.txt";  // Replace with actual file path
$fileStream = $lara->documents->translate($filePath, "en-US", "fr-FR");

// With options
$options = new DocumentTranslateOptions();
$options->setAdaptTo(["mem_1A2b3C4d5E6f7G8h9I0jKl"]);  // Replace with actual memory IDs
$options->setGlossaries(["gls_1A2b3C4d5E6f7G8h9I0jKl"]);  // Replace with actual glossary IDs

$fileStream = $lara->documents->translate($filePath, "en-US", "fr-FR", $options);
```
### Document translation with status monitoring
#### Document upload
```php
//Optional: upload options
$uploadOptions = new DocumentUploadOptions();
$uploadOptions->setAdaptTo(["mem_1A2b3C4d5E6f7G8h9I0jKl"]);  // Replace with actual memory IDs
$uploadOptions->setGlossaries(["gls_1A2b3C4d5E6f7G8h9I0jKl"]);  // Replace with actual glossary IDs

$document = $lara->documents->upload($filePath, "en-US", "fr-FR", $uploadOptions);
```
#### Document translation status monitoring
```php
$status = $lara->documents->status($document->getId());
```
#### Download translated document
```php
$downloadOptions = new DocumentDownloadOptions();

$fileStream = $lara->documents->download($document->getId(), $downloadOptions);
```

### 🧠 Memory Management

```php
// Create memory
$memory = $lara->memories->create("MyMemory");

// Create memory with external ID (MyMemory integration)
$memory = $lara->memories->create("Memory from MyMemory", "aabb1122");  // Replace with actual external ID

// Important: To update/overwrite a translation unit you must provide a tuid. Calls without a tuid always create a new unit and will not update existing entries.
// Add translation to single memory
$memoryImport = $lara->memories->addTranslation("mem_1A2b3C4d5E6f7G8h9I0jKl", "en-US", "fr-FR", "Hello", "Bonjour", "greeting_001");

// Add translation to multiple memories
$memoryImport = $lara->memories->addTranslation(
    ["mem_1A2b3C4d5E6f7G8h9I0jKl", "mem_2XyZ9AbC8dEf7GhI6jKlMn"],  // Replace with actual memory IDs
    "en-US", "fr-FR", "Hello", "Bonjour", "greeting_002"
);

// Add with context
$memoryImport = $lara->memories->addTranslation(
    "mem_1A2b3C4d5E6f7G8h9I0jKl", "en-US", "fr-FR", "Hello", "Bonjour", "tuid", 
    "sentenceBefore", "sentenceAfter"
);

// TMX import from file
$tmxFilePath = "/path/to/your/memory.tmx";  // Replace with actual TMX file path
$memoryImport = $lara->memories->importTmx("mem_1A2b3C4d5E6f7G8h9I0jKl", $tmxFilePath);

// Delete translation
// Important: if you omit tuid, all entries that match the provided fields will be removed
$deleteJob = $lara->memories->deleteTranslation(
    "mem_1A2b3C4d5E6f7G8h9I0jKl", "en-US", "fr-FR", "Hello", "Bonjour", "greeting_001"
);

// Wait for import completion
$completedImport = $lara->memories->waitForImport($memoryImport, 300); // 5 minutes
```

### 📚 Glossary Management

```php
// Create glossary
$glossary = $lara->glossaries->create("MyGlossary");

// Import CSV from file
$csvFilePath = "/path/to/your/glossary.csv";  // Replace with actual CSV file path
$glossaryImport = $lara->glossaries->importCsv("gls_1A2b3C4d5E6f7G8h9I0jKl", $csvFilePath);

// Check import status
$importStatus = $lara->glossaries->getImportStatus($glossaryImport->getId());

// Wait for import completion
$completedImport = $lara->glossaries->waitForImport($glossaryImport, 300); // 5 minutes

// Export glossary
$csvData = $lara->glossaries->export("gls_1A2b3C4d5E6f7G8h9I0jKl", "csv/table-uni", "en-US");

// Get glossary terms count
$counts = $lara->glossaries->counts("gls_1A2b3C4d5E6f7G8h9I0jKl");
```

### Translation Options

```php
// Constructor array pattern (recommended)
$options = new TranslateOptions([
    'adaptTo' => ["mem_1A2b3C4d5E6f7G8h9I0jKl"],              // Memory IDs to adapt to
    'glossaries' => ["gls_1A2b3C4d5E6f7G8h9I0jKl"],           // Glossary IDs to use
    'instructions' => ["instruction"],                        // Translation instructions
    'style' => "fluid",                                       // Translation style (fluid, faithful, creative)
    'contentType' => "text/plain",                            // Content type (text/plain, text/html, etc.)
    'multiline' => true,                                      // Enable multiline translation
    'timeoutInMillis' => 10000,                               // Request timeout in milliseconds
    'sourceHint' => "en",                                     // Hint for source language detection
    'noTrace' => false,                                       // Disable request tracing
    'verbose' => false,                                       // Enable verbose response
]);

// Alternative setter pattern
$options = new TranslateOptions();
$options->setSourceHint("en-US");
$options->setAdaptTo(["mem_1A2b3C4d5E6f7G8h9I0jKl", "mem_2XyZ9AbC8dEf7GhI6jKlMn"]);  // Replace with actual memory IDs
$options->setInstructions(["Formal tone", "Use technical terminology"]);
$options->setGlossaries(["gls_1A2b3C4d5E6f7G8h9I0jKl", "gls_2XyZ9AbC8dEf7GhI6jKlMn"]);  // Replace with actual glossary IDs
$options->setContentType("text/html");
$options->setMultiline(true);
$options->setTimeoutInMillis(15000);
$options->setNoTrace(false);
$options->setVerbose(true);
$options->setStyle("faithful");
```

### Language Codes

The SDK supports full language codes (e.g., `en-US`, `fr-FR`, `es-ES`) as well as simple codes (e.g., `en`, `fr`, `es`):

```php
// Full language codes (recommended)
$result = $lara->translate("Hello", "en-US", "fr-FR");

// Simple language codes
$result = $lara->translate("Hello", "en", "fr");
```

### 🌐 Supported Languages

The SDK supports all languages available in the Lara API. Use the `getLanguages()` method to get the current list:

```php
$languages = $lara->getLanguages();
echo "Supported languages: " . implode(', ', $languages) . PHP_EOL;
```

## ⚙️ Configuration

### Error Handling

The SDK provides detailed error information:

```php
try {
    $result = $lara->translate("Hello", "en-US", "fr-FR");
    echo "Translation: " . $result->getTranslation() . PHP_EOL;
} catch (LaraException $e) {
    echo "API Error: " . $e->getMessage() . PHP_EOL;
} catch (LaraTimeoutException $e) {
    echo "Timeout Error: " . $e->getMessage() . PHP_EOL;
}
```

## 📋 Requirements

- PHP 7.4 or higher
- Composer
- Valid Lara API credentials

## 🧪 Testing

Run the examples to test your setup:

```bash
# All examples use environment variables for credentials, so set them first:
export LARA_ACCESS_KEY_ID="your-access-key-id"
export LARA_ACCESS_KEY_SECRET="your-access-key-secret"
```
```bash
# Run basic text translation example
cd examples
php text_translation.php
```

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

Happy translating! 🌍✨
