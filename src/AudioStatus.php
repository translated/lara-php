<?php

namespace Lara;

class AudioStatus
{
    const INITIALIZED = "initialized";  // just been created
    const ANALYZING = "analyzing";      // being analyzed for language detection and chars count
    const PAUSED = "paused";            // paused after analysis, needs user confirm
    const READY = "ready";              // ready to be translated
    const TRANSLATING = "translating";
    const TRANSLATED = "translated";
    const ERROR = "error";
}
