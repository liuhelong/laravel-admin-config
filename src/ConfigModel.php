<?php

namespace Liuhelong\Config;

use Illuminate\Database\Eloquent\Model;
use Storage;

class ConfigModel extends Model
{
    /**
     * Settings constructor.
     *
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        $this->setConnection(config('admin.database.connection') ?: config('database.default'));

        $this->setTable(config('admin.extensions.config.table', 'admin_config'));
    }

    /**
     * Set the config's value.
     *
     * @param string|null $value
     */
    public function setValueAttribute($value = null)
    {
        if (config('admin.extensions.config.valueEmptyStringAllowed', false)) {
            $this->attributes['value'] = is_null($value) ? '' : $value;
        } else {
            $this->attributes['value'] = $value;
        }
    }
    public function setFileAttribute($value)
    {
        if(url()->isValidUrl($value)){
			$path = $value;
		}else{
			$path = Storage::disk('admin')->url($value);
		}
		
        $this->attributes['value'] = $path;
    }
	public function getFileAttribute()
    {
        return $this->value;
    }
}
