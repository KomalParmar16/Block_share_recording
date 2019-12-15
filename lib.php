<?php 
function block_simplehtml_images() {
    return array(html_writer::tag('img', '', array('alt' => get_string('red', 'block_simplehtml'), 'src' => "pix/picture0.gif")),
                html_writer::tag('img', '', array('alt' => get_string('blue', 'block_simplehtml'), 'src' => "pix/picture1.gif")),
                html_writer::tag('img', '', array('alt' => get_string('green', 'block_simplehtml'), 'src' => "pix/picture2.gif")));
}
?>