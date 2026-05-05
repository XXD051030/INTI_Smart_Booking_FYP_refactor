<?php

declare(strict_types=1);

namespace V2\Support;

final class Translator
{
    public const FALLBACK_LOCALE = 'en';

    /** @var array<string, array<string, string>> */
    private array $loaded = [];

    private string $locale;

    /**
     * @param array<int, string> $availableLocales
     */
    public function __construct(
        private readonly string $langPath,
        private readonly array $availableLocales,
        string $defaultLocale,
        ?string $sessionLocale,
    ) {
        $candidate = $sessionLocale ?? $defaultLocale;
        $this->locale = in_array($candidate, $availableLocales, true) ? $candidate : self::FALLBACK_LOCALE;
    }

    public function locale(): string
    {
        return $this->locale;
    }

    /**
     * @return array<int, string>
     */
    public function availableLocales(): array
    {
        return $this->availableLocales;
    }

    public function get(string $key, ?string $fallback = null): string
    {
        $current = $this->dictionary($this->locale);
        if (array_key_exists($key, $current)) {
            return $current[$key];
        }

        if ($this->locale !== self::FALLBACK_LOCALE) {
            $base = $this->dictionary(self::FALLBACK_LOCALE);
            if (array_key_exists($key, $base)) {
                return $base[$key];
            }
        }

        return $fallback ?? $key;
    }

    /**
     * @return array<string, string>
     */
    private function dictionary(string $locale): array
    {
        if (isset($this->loaded[$locale])) {
            return $this->loaded[$locale];
        }

        $file = $this->langPath . '/' . $locale . '.php';
        $data = is_file($file) ? require $file : [];
        $this->loaded[$locale] = is_array($data) ? $data : [];

        return $this->loaded[$locale];
    }
}
