<?php

namespace App\Models;

use App\Traits\HasAuditFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory, HasAuditFields;

    protected $fillable = ['key', 'value', 'created_by', 'updated_by'];

    /**
     * Get a setting value by key
     */
    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value by key
     */
    public static function set($key, $value)
    {
        $setting = self::where('key', $key)->first();
        
        if ($setting) {
            $setting->value = $value;
            if (auth()->check()) {
                $setting->updated_by = auth()->id();
            }
            $setting->save();
            return $setting;
        } else {
            $data = ['key' => $key, 'value' => $value];
            if (auth()->check()) {
                $data['created_by'] = auth()->id();
                $data['updated_by'] = auth()->id();
            }
            return self::create($data);
        }
    }
}
