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
        global $COURSE, $DB,$PAGE; 

        if (!empty($this->config->text)) {
            $this->content->text = $this->config->text;
        }
        
        // This is the new code.
        $canmanage = $PAGE->user_is_editing($this->instance->id);
        if ($simplehtmlpages = $DB->get_records('block_simplehtml', array('blockid' => $this->instance->id))) {
            $this->content->text .= html_writer::start_tag('ul');
            foreach ($simplehtmlpages as $simplehtmlpage) {
                if ($canmanage) {
                    $pageparam = array('blockid' => $this->instance->id, 'courseid' => $COURSE->id,'id' => $simplehtmlpage->id);
                    $editurl = new moodle_url('/blocks/simplehtml/view.php', $pageparam);
                    $editpicurl = new moodle_url('/pix/t/edit.gif');
                    $edit = html_writer::link($editurl, html_writer::tag('img', '', array('src' => $editpicurl, 'alt' => get_string('edit'))));
                    //delete
                    $deleteparam = array('id' => $simplehtmlpage->id, 'courseid' => $COURSE->id);
                    $deleteurl = new moodle_url('/blocks/simplehtml/delete.php', $deleteparam);
                    $deletepicurl = new moodle_url('/pix/t/delete.gif');
                    $delete = html_writer::link($deleteurl, html_writer::tag('img', '', array('src' => $deletepicurl, 'alt' => get_string('delete'))));
                } else {
                    $edit = '';
                    $delete = '';
                }
                $pageurl = new moodle_url('/blocks/simplehtml/view.php', array('blockid' => $this->instance->id, 'courseid' => $COURSE->id, 'id' => $simplehtmlpage->id, 'viewpage' => true));
                $this->content->text .= html_writer::start_tag('li');
                $this->content->text .= html_writer::link($pageurl, $simplehtmlpage->pagetitle);
                $this->content->text .= $edit;
                $this->content->text .= $delete;
                $this->content->text .= html_writer::end_tag('li');
            }
            $this->content->text .= html_writer::end_tag('ul');
        }
                // The other code.
                
                $url = new moodle_url('/blocks/simplehtml/view.php', array('blockid' => $this->instance->id, 'courseid' => $COURSE->id));
                $this->content->footer = html_writer::link($url, get_string('addpage', 'block_simplehtml'));
                    
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
      public function instance_delete() {
        global $DB;
        $DB->delete_records('block_simplehtml', array('blockid' => $this->instance->id));
    }
}