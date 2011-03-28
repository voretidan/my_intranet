<?php
/**
 * Html Helper charset function
 *
 * Return a valid html charset meta tag
 *
 * @return string
 */
function html_charset() {
    return html__output('<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />');
}
/**
 * Html Helper css function
 *
 * Output a valid css file reference.  Can be used to link to multiple files at
 * the same time or just the one file.  .css extension is optional as it will be
 * added if it's missing.  Options are:
 *
 * $options = array(
 *  'base' => true or false, // is this file local or remote (local = true)
 *  'ext' => '.css', // the extension to add on the end if none is present
 *  'media' => 'screen, projection', // the css media property
 * );
 *
 *
 * @param mixed $src address of the file to link to or array of addresses
 * @param array $options array of options as defined
 * @return string
 */
function html_css($src, $options = array()) {
    $defaults = array(
        'base' => true,
        'ext' => '.css',
        'media' => 'screen, projection'
    );

    $options = array_merge($defaults, $options);

    if(is_array($src)) {
        $output = '';
        foreach($src as $item) {
            $output .= html_css($item, $options) . "\r\n";
        }

        return html__output($output);
    }

    if(!empty($options['ext']) && !stristr($src, $options['ext'])) {
         $src = $src . $options['ext'];
    }
    
    if($options['base']) {
        return html__output('<link rel="stylesheet" href="' . BASE . 'css/' . $src . '" type="text/css" media="' . $options['media'] . '" />');
    } else {
        return html__output('<link rel="stylesheet" href="' . $src . '" type="text/css" media="' . $options['media'] . '" />');
    }
}
/**
 * Html Helper cycle function
 *
 * Alternates row classes without all the messing around
 *
 * @global int $cycleCount variable to keep track of our cycles
 * @param array $options different classes to cycle through
 * @param bool $classTag include the class tag or just return the class
 * @return string the text to output
 */
$cycleCount = 0;
function html_cycle($options = array('odd', 'even'), $classTag = false) {
    global $cycleCount;

    $optionsCount = count($options) - 1;

    if($classTag) {
        $return = 'class="'. $options[$cycleCount]. '"';
    } else {
        $return = $options[$cycleCount];
    }

    if($cycleCount < $optionsCount) {
        $cycleCount++;
    } else {
        $cycleCount = 0;
    }

    return $return;
}
/**
 * Html Helper ie only tags
 *
 * @param string $code the code to include inside the tags
 * @param array $options array of options as defined
 * @return string
 */
function html_ie_only($code, $options = array()) {
    $defaults = array(
        'comparison' => '',
        'tabs' => "\t\t",
        'version' => ''
    );

    $options = array_merge($defaults, $options);

    $string = 'if';
    if(!empty($options['comparison'])) {
        $string .= ' ' . $options['comparison'];
    }

    $string .= ' IE';
    if(!empty($options['version'])) {
        $string .= ' ' . $options['version'];
    }

    return html__output("<!--[{$string}]>\r\n{$options['tabs']}\t{$code}\r\n{$options['tabs']}<![endif]-->");
}
/**
 * Html Helper image function
 *
 * Echo a valid image tag using $src and $options.  Set option $base to false if
 * the image is remote (not in the local img directory)
 *
 * @param string $src the location of the image
 * @param array $options array of options as defined
 * @return string
 */
function html_image($src, $options = array()) {
    $defaults = array(
        'alt' => '',
        'base' => true
    );

    $options = array_merge($defaults, $options);

    $attributes = '';
    foreach($options as $key => $value) {
        if($key != 'base') {
            $attributes .= " {$key}=\"{$value}\"";
        }
    }

    if($options['base']) {
        return html__output('<img src="' . BASE . 'img/' . $src . '"' . $attributes . ' />');
    } else {
        return html__output('<img src="' . $src . '"' . $attributes . ' />');
    }
}
/**
 * Html Helper link function
 *
 * Create a html link to another file or location.  Set $options['base'] to
 * false if the link is remote/not on this site
 *
 * @param string $text the text for the link
 * @param string $link the file/page to link to
 * @param array $options array of options as defined
 * @return string
 */
function html_link($text, $link, $options = array()) {
    $defaults = array(
        'base' => true,
        'span' => true
    );

    if($link == '#' && empty($options['base'])) {
        $options['base'] = false;
    }

    $options = array_merge($defaults, $options);

    if($options['span']) {
        $text = '<span>' . $text . '</span>';
    }

    if($options['base']) {
        $link = BASE . $link;
    }

    $attributes = '';
    foreach($options as $key => $value) {
        if($key != 'base' && $key != 'span') {
            $attributes .= " {$key}=\"{$value}\"";
        }
    }

    return html__output( '<a href="' . $link . '"' . $attributes . '>' . $text . '</a>');

}
/**
 * Html Helper menu fuction
 *
 * Use html_link to make a nice simple menu
 *
 * @param array $items array of pages to link to
 * @param array $options array of options as defined
 * @param boolean $home include a link to the homepage or not
 * @return string
 */
function html_menu($items, $options = array(), $home = false) {
    $defaults = array(
        'accordion' => false,
        'current_class' => 'active'
    );

    $options = array_merge($defaults, $options);

    if($options['accordion']) {
        return html__accordion($items);
    }

    $a = '';

    if(!empty($options['class'])) {
        $a .= " class=\"{$options['class']}\"";
    }

    if(!empty($options['id'])) {
        $a .= " id=\"{$options['id']}\"";
    }

    $output = "<ul{$a}>\r\n";

    if($home) {
        $output .= "<li id=\"home\">" . html_link('Home', BASE, array('base' => false)) . "</li>\r\n";
    }

    foreach($items as $text => $link) {
        if(!empty($_GET['url']) && stristr($_GET['url'], $link)) {
            if(is_array($link)) {
                if(empty($link['class'])) {
                    $link['class'] = $options['current_class'];
                } else {
                    $link['class'] .= $options['current_class'] . ' ' . $link['class'];
                }
            } else {
                $link = array(
                    'link' => $link,
                    'class' => $options['current_class']
                );
            }
        }

        if(is_array($link)) {
            if(!empty($link['class'])) {
                $output .= '<li class="' . $link['class'] . '">';
            } else {
                $output .= '<li>';
            }

            if(empty($link['options'])) {
                $link['options'] = array();
            }

            $output .= html_link($text, $link['link'], $link['options']);

            if(!empty($link['children'])) {
                $output .= html_menu($link['children']);
            }

            $output .=  "</li>\r\n";
        } else {
            $output .= "<li>" . html_link($text, $link) . "</li>\r\n";
        }
    }

    $output .= "</ul>\r\n";

    return html__output($output);
}
/**
 * Html Helper script block function
 *
 * Return valid html code to wrap a block of javascript code
 *
 * @param string $code the code to wrap
 * @return string
 */
function html_script_block($code) {
    return html__output('<script type="text/javascript">' . $code . '</script>');
}
/**
 * Html Helper script link
 *
 * Return valid html code to include a javascript file.  Could also include
 * several files at once by passing an array to src.  The .js extension is
 * optional as with .css and html_css above and will be added automatically if
 * ommitted
 *
 * @param mixed $src string of file to link to or array of files to link to
 * @param array $options array of options as defined
 * @return return
 */
function html_script_link($src, $options = array()) {
    $defaults = array(
        'base' => true,
        'ext' => '.js'
    );

    $options = array_merge($defaults, $options);

    if(is_array($src)) {
        $output = '';
        foreach($src as $item) {
            $output .= html_script_link($item, $options) . "\r\n";
        }

        return html__output($output);
    }

    if(!empty($options['ext']) && !stristr($src, $options['ext'])) {
        $src . $options['ext'];
    }

    if($options['base']) {
        return html__output('<script type="text/javascript" src="' . BASE . 'js/' . $src . '"></script>');
    } else {
        return html__output('<script type="text/javascript" src="' . $src . '"></script>');
    }
}
/**
 * Html Helper style function
 *
 * Return valid html code for embedding css in a page
 *
 * @param string $css
 * @param array $options array of options as defined
 * @return string
 */
function html_style($css, $options = array()) {
    $defaults = array(
        'tabs' => "\t\t"
    );

    $options = array_merge($defaults, $options);

    if(is_array($css)) {
        $temp = '';
        foreach($css as $style) {
            $temp .= (empty($temp)) ? $style : "\r\n{$options['tabs']}\t{$style}";
        }
        $css = $temp;
        unset($temp);
    }

    return html__output("<style type=\"text/css\">\r\n{$options['tabs']}\t{$css}\r\n{$options['tabs']}</style>");
}
/**
 * Html Helper url function
 *
 * Return a valid url for a local page
 *
 * @param string $url file/page to link to
 * @return string
 */
function html_url($url) {
    return html__output(BASE . $url, array('newline' => false));
}
/**
 * Html pagination function
 *
 * Return links to the next and previous pages
 *
 * @return string
 */
function html_pagination($show_previous = true, $show_next = true) {
    if(!empty($_GET['page'])) {
        $current_page = $_GET['page'];
    } else {
        $current_page = 0;
        $show_next = false;
    }

    $output = '<ol class="paginate">';

    if($show_previous) {
        $output .= '<li class="previous">';
        $output .= html_link('&lt; Previous', '?page=' . ++$current_page, array('class' => 'page'));
        $output .= '</li>';
    }

    if($show_next) {
        $output .= '<li class="next">';
        $output .= html_link('&gt; Next', '?page=' . --$current_page, array('class' => 'page'));
        $output .= '</li>';
    }

    $output .= '</ol>';

    return html__output($output);
}
/**
 * Html Helper accordion function
 *
 * For internal use only, returns a special case for an accordion menu
 *
 * @param array $items list of items to use
 * @return string
 */
function html__accordion($items) {
    $output = '';
    foreach($items as $heading => $links) {
        $output .= "<h3>" . $heading . "</h3>\r\n";
        $output .= "<div>\r\n";
        $output .= html_menu($links);
        $output .= "</div>\r\n";
    }

    return $output;
}
/**
 * Html Helper output function
 *
 * For internal use only, for passing all html before output from a function so
 * we can so stuff to it if we need to
 *
 * @param string $html html to output
 * @param array $options array of options as defined
 * @return string
 */
function html__output($html, $options = array()) {
    $defaults = array(
        'newline' => true
    );

    $options = array_merge($defaults, $options);

    if($options['newline']) {
        return $html . "\r\n";
    } else {
        return $html;
    }
}