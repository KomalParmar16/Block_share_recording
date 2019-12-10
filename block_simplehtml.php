<?php
class block_simplehtml extends block_base {
    public function init() {
        $this->title = get_string('simplehtml', 'block_simplehtml');
    }
    // The PHP tag and the curly bracket for the class definition 
    // will only be closed after there is another function added in the next section.
    public function get_content() {
        if ($this->content !== null) {
          return $this->content;
        }
     
        $this->content         =  new stdClass;
        $this->content->text   = 'The content of our SimpleHTML block!';
        $this->content->footer = 'Footer here...';
     
        return $this->content;
    }
    public function specialization() {
        if (isset($this->config)) {
            if (empty($this->config->title)) {
                $this->title = get_string('defaulttitle', 'block_simplehtml');            
            } else {
                $this->title = $this->config->title;
            }
     
            if (empty($this->config->text)) {
                $this->config->text = get_string('defaulttext', 'block_simplehtml');
            }    
        }
    }
    function has_config() {return true;}

    public function instance_config_save($data, $nolongerused = false) {
        if(get_config('simplehtml', 'Allow_HTML') == '1') {
            $data->text = strip_tags($data->text);
          }
         
          // And now forward to the default implementation defined in the parent class
          return parent::instance_config_save($data,$nolongerused);
      }
      public function hide_header() {
        return true;
      }
      public function html_attributes() {
        $attributes = parent::html_attributes(); // Get default values
        $attributes['class'] .= ' block_'. $this->name(); // Append our class to class attribute
        return $attributes;
    }
    public function applicable_formats() {
        return array(
                 'site-index' => true,
                'course-view' => true, 
         'course-view-social' => false,
                        'mod' => true, 
                   'mod-quiz' => false
        );
      }
}