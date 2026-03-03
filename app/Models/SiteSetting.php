<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['key', 'value'];

    public static function defaults(): array
    {
        return [
            'couleur_bleu'       => '#1130dc',
            'couleur_bleu_fonce' => '#0917c2',
            'couleur_orange'     => '#ea4f2a',
            'site_titre'         => 'Quiz FAC Andrézieux',
            'site_sous_titre'    => 'Testez vos connaissances sur l\'athlétisme et le club',
            'logo_path'          => 'img/logo.gif',
        ];
    }

    public static function get(string $key): ?string
    {
        $record = self::find($key);
        return $record ? $record->value : (self::defaults()[$key] ?? null);
    }

    public static function set(string $key, ?string $value): void
    {
        self::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public static function all_settings(): array
    {
        $db = self::all()->pluck('value', 'key')->toArray();
        return array_merge(self::defaults(), $db);
    }
}
