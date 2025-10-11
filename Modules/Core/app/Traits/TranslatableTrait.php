<?php

namespace Modules\Core\Traits;

use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Stichoza\GoogleTranslate\GoogleTranslate;

trait TranslatableTrait
{
    /**
     * Translate content to a specific language using Google Translate.
     */
    public function autoGoogleTranslator(string $targetLang, string $content): string
    {
        $translator = new GoogleTranslate;

        return $translator->setTarget($targetLang)->translate($content);
    }

    /**
     * Return all supported languages except the current locale.
     */
    public function otherLangs(): array
    {
        $locale = app()->getLocale();
        $supportedLocales = array_keys(LaravelLocalization::getSupportedLocales());

        return array_values(array_filter($supportedLocales, fn ($lang) => $lang !== $locale));
    }
}
