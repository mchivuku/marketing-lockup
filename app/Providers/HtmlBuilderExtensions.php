<?php
/**
 * Created by PhpStorm.
 * User: mchivuku
 * Date: 6/19/15
 * Time: 2:27 PM
 */


\HTML::macro('left_navigation',

    function ($items, $attributes = array()) {

        $default_selected = 'current-step';
        $html = "<ul" . \HTML::attributes($attributes) . ">";

        foreach ($items as $liItem) {

            if (\Route::currentRouteName() == $liItem['route_name'] || (is_array($liItem['route_name']) && in_array
                    (\Route::currentRouteName(),
                        $liItem['route_name']))
            ) {

                $html .= "<li class='" . $default_selected . "'>";

            } else {
                $html .= "<li>";
            }

            $html .= $liItem['text'];

            $html .= "</li>";

        }

        $html .= "</ul>";

        return $html;

    });