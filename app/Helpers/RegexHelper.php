<?php

namespace App\Helpers;

class RegexHelper {
    private const SCRIPT_TAGS_REGEX = '<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>';
    private const EVENT_ATTRIBUTES_REGEX = '\bon\w+\s*=\s*["\']([^"\']*)["\']';
    private const FUNCTION_REGEX = '(function\s*\w*\s*\([^)]*\)\s*{[\s\S]*?\})|(\b\w+\s*=\s*function\s*\([^)]*\)\s*{[\s\S]*?\})|(\(\s*[^)]*\s*\)\s*=>\s*{[\s\S]*?\})';
    private const JAVASCRIPT_REGEX = '<script\b[^<]*(?:(?!<\/script>)<[^<]*)*>([\s\S]*?)<\/script>';
    private const CONTAINS_SRC = '<[^>]+src\s*=\s*["\'][^"\']*["\'][^>]*>';
    private const IMG_IFRAME_TAG = '<(img|iframe)[^>]*>';
    
    const RTE_REGEX = '/(' . self::SCRIPT_TAGS_REGEX . ')|(' . self::FUNCTION_REGEX . ')|(' . self::EVENT_ATTRIBUTES_REGEX . ')|(' . self::JAVASCRIPT_REGEX . ')/i';
    const INPUT_TEXT_REGEX = '/^[a-zA-Z0-9.,?\-!"\s:%\']+$/';
    const INPUT_PHONE_REGEX = '/^\d{9,13}$/';
}
