<?php
/**
 * Created by PhpStorm.
 * User: mchivuku
 * Date: 6/12/15
 * Time: 10:00 AM
 */

namespace App\Models\ViewModels;

class ViewSettings
{
    public static function renderAttributes($attributes){
        $attribute = function($key,$value){
            if (is_numeric($key)) $key = $value;
            if ( ! is_null($value)) return $key.'="'.e($value).'"';
        };

        foreach ((array) $attributes as $key => $value)
        {
            $element = $attribute($key, $value);

            if ( ! is_null($element)) $html[] = $element;
        }

        return count($html) > 0 ? ' '.implode(' ', $html) : '';

    }


}
