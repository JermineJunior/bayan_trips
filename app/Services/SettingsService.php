<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    /**
     * Cache key prefix. Each setting is cached individually so a single
     * update only invalidates its own entry.
     */
    public const CACHE_PREFIX = 'settings.';

    /**
     * Key of the settings row that hosts the subsidiary company columns.
     */
    public const COMPANY_ROW_KEY = 'company';

    /**
     * Settings backed by columns on the COMPANY_ROW_KEY row instead of the
     * generic value column. They share the exact same get()/set() API.
     */
    private const COLUMN_KEYS = ['company_phone', 'company_location', 'reports_message'];

    /**
     * Read a setting, falling back to the given default when the key is
     * missing or has no value.
     *
     * Company settings are read from the columns of the company settings row.
     * Results are cached indefinitely and the cache entry is forgotten
     * whenever the setting is written.
     */
    public function get(string $key, ?string $default = null): ?string
    {
        return Cache::rememberForever(
            self::CACHE_PREFIX.$key,
            fn (): ?string => $this->readRaw($key) ?? $default,
        );
    }

    /**
     * Persist a setting and invalidate its cached value.
     *
     * Company settings are written to the columns of the company settings row.
     */
    public function set(string $key, ?string $value): void
    {
        if (in_array($key, self::COLUMN_KEYS, true)) {
            Setting::updateOrCreate(
                ['key' => self::COMPANY_ROW_KEY],
                [$key => $value],
            );

            $this->forget($key);

            return;
        }

        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value],
        );

        $this->forget($key);
    }

    /**
     * Read a setting's stored value without touching the cache or default.
     */
    protected function readRaw(string $key): ?string
    {
        if (in_array($key, self::COLUMN_KEYS, true)) {
            return Setting::where('key', self::COMPANY_ROW_KEY)->first()?->{$key};
        }

        return Setting::find($key)?->value;
    }

    /**
     * Whether a setting has a non-null value.
     */
    public function has(string $key): bool
    {
        return $this->get($key) !== null;
    }

    /**
     * Remove the cached value for a setting.
     */
    public function forget(string $key): void
    {
        Cache::forget(self::CACHE_PREFIX.$key);
    }
}
