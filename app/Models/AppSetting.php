<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function put(string $key, ?string $value): self
    {
        return static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value],
        );
    }

    public static function whatsappChannelLink(): ?string
    {
        return static::where('key', 'whatsapp_channel_link')->value('value');
    }

    public static function adminSignatureName(): ?string
    {
        return static::query()->where('key', 'admin_signature_name')->value('value');
    }

    public static function adminSignaturePath(): ?string
    {
        return static::query()->where('key', 'admin_signature_path')->value('value');
    }

    public static function competencyLetterSignatoryName(): ?string
    {
        return static::query()->where('key', 'competency_letter_signatory_name')->value('value');
    }

    public static function competencyLetterSignaturePath(): ?string
    {
        return static::query()->where('key', 'competency_letter_signature_path')->value('value');
    }

    public static function competencyLetterStampPath(): ?string
    {
        return static::query()->where('key', 'competency_letter_stamp_path')->value('value');
    }

    public static function hasCompetencyLetterAssets(): bool
    {
        return filled(static::competencyLetterSignatoryName())
            && filled(static::competencyLetterSignaturePath())
            && filled(static::competencyLetterStampPath());
    }
}
