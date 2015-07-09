<?php

function navigation($items, $attributes = array()) {

        /* If we are on the current page we don't construct a link */
		$render_attributes = function($attributes){
		 $result="";
		 foreach ($attributes as $attribute => $value) {
			 if($value!="")
				 $result .= " {$attribute}=\"{$value}\"";
		 }
		};

        $default_selected = 'active';
        $html = "<ul class=\"nav\" " . $render_attributes($attributes) . ">";

        foreach ($items as $liItem) {

            if (\Route::currentRouteName() == $liItem['route_name'] || (is_array($liItem['route_name']) && in_array
                    (\Route::currentRouteName(),
                        $liItem['route_name']))
            ) {

                $html .= "<li class='" . $default_selected . "'>";

            } else {
                $html .= "<li>";
            }

			$html.= sprintf("<a href='%s'>%s</a>",$liItem['url'],$liItem['text']);

            $html .= "</li>";

        }

        $html .= "</ul>";

        return $html;

};

echo navigation($navigation);

?>


