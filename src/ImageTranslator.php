<?php

namespace Lara;

class ImageTranslator
{
    /**
     * @var Internal\HttpClient
     */
    private $client;

    /**
     * @param $client Internal\HttpClient
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * Translate an image, returning a stream of the translated image
     *
     * @param $filePath string path to the image file
     * @param $source string|null source language
     * @param $target string target language
     * @param $options ImageTranslationOptions|null
     * @return resource stream of the translated image
     * @throws LaraException
     */
    public function translate($filePath, $source, $target, $options = null)
    {
        $data = ["target" => $target];
        $headers = [];

        if ($source) $data["source"] = $source;

        if ($options) {
            if ($options->getAdaptTo() !== null) $data["adapt_to"] = json_encode(array_values($options->getAdaptTo()));
            if ($options->getGlossaries() !== null) $data["glossaries"] = json_encode(array_values($options->getGlossaries()));
            if ($options->getStyle() !== null) $data["style"] = $options->getStyle();
            $model = $options->getModel() !== null ? $options->getModel() : $options->getTextRemoval();
            if ($model !== null) $data["model"] = $model;
            if ($options->isNoTrace()) $headers["X-No-Trace"] = "true";
        }

        $files = ["image" => $filePath];

        return $this->client->postBinaryStream("/v2/images/translate", $data, $files, $headers);
    }

    /**
     * Extract and translate text from an image
     * Layout is included only when requested and available from the extractor.
     *
     * @param $filePath string path to the image file
     * @param $source string|null source language
     * @param $target string target language
     * @param $options ImageTextTranslationOptions|null
     * @return ImageTextResult
     * @throws LaraException
     */
    public function translateText($filePath, $source, $target, $options = null)
    {
        $data = ["target" => $target];
        $headers = [];

        if ($source) $data["source"] = $source;

        if ($options) {
            if ($options->getAdaptTo() !== null) $data["adapt_to"] = json_encode(array_values($options->getAdaptTo()));
            if ($options->getGlossaries() !== null) $data["glossaries"] = json_encode(array_values($options->getGlossaries()));
            if ($options->getStyle() !== null) $data["style"] = $options->getStyle();
            if ($options->isVerbose() !== null) $data["verbose"] = json_encode($options->isVerbose());
            if ($options->getIncludeLayout() !== null) $data["include_layout"] = json_encode($options->getIncludeLayout());
            if ($options->isNoTrace()) $headers["X-No-Trace"] = "true";
        }

        $files = ["image" => $filePath];

        return ImageTextResult::fromResponse($this->client->post("/v2/images/translate-text", $data, $files, $headers));
    }

    /**
     * Render supplied translations without translating them again.
     * Overlay and inpainting require ImageLayoutParagraph entries with complete layout.
     * Generative models also accept text-only ImageParagraph entries.
     * The API validates paragraph contents and uses generative_fast when model is omitted.
     *
     * @param $filePath string path to the original image file
     * @param $source string|null source language; null to omit it
     * @param $target string target language
     * @param $paragraphs ImageParagraph[]
     * @param $model string|null "overlay", "inpainting", "generative" or "generative_fast"
     * @param $noTrace bool whether to disable request tracing
     * @return resource stream of the rendered image; the caller must close it
     * @throws LaraException
     */
    public function renderTranslated($filePath, $source, $target, $paragraphs, $model = null, $noTrace = false)
    {
        // Select rendering fields so verbose matches are not sent back to the API.
        $renderParagraphs = [];
        foreach ($paragraphs as $paragraph) {
            $fields = [
                'text' => $paragraph->getText(),
                'translation' => $paragraph->getTranslation()
            ];
            if ($paragraph instanceof ImageLayoutParagraph) {
                $fields['bbox'] = $paragraph->getBbox();
                $fields['lines_bboxes'] = array_values($paragraph->getLinesBboxes());
                $fields['text_info'] = $paragraph->getTextInfo();
                $fields['alignment'] = $paragraph->getAlignment();
            }
            $renderParagraphs[] = $fields;
        }

        $data = ["target" => $target, "paragraphs" => json_encode($renderParagraphs)];
        if ($source) $data["source"] = $source;
        if ($model !== null) $data["model"] = $model;

        $headers = [];
        if ($noTrace) $headers["X-No-Trace"] = "true";

        return $this->client->postBinaryStream(
            "/v2/images/render-translated", $data, ["image" => $filePath], $headers
        );
    }
}
