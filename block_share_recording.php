<?php
class block_share_recording extends block_base
{
    public function init()
    {
        $this->title = get_string('pluginname', 'block_share_recording');
    }
    
    public function applicable_formats()
    {
        return array(
                 'site-index' => true,
                'course-view' => true,
         'course-view-social' => false,
                        'mod' => true,
                        'my'  => true,
                   'mod-quiz' => false
        );
    }

    public function has_config()
    {
        return true;
    }

    public function get_content() {

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->footer = '';
        $this->content->text   = '';

        global $DB, $CFG, $COURSE;
        if (empty($this->instance)) {
            return $this->content;
        }
        if ($recordinglists = $DB->get_records('block_share_recording')) {
            $this->content->text .= html_writer::start_tag('ul');
            foreach ($recordinglists as $recordinglist) {
                $deleteparam = array('id' => $recordinglist->id, 'courseid' => $COURSE->id);
                $deleteurl = new moodle_url('/blocks/share_recording/delete.php', $deleteparam);
                if (has_capability('block/sharerecording:managepages', context_course::instance($COURSE->id))) {
                    $imageurl = "$CFG->wwwroot/blocks/share_recording/pix/delete.png";
                    $buttons = html_writer::link(new moodle_url($deleteurl, array(
                        'deleterecording' => $recordinglist->sessionid,
                        'recordingname' => $recordinglist->recordingname
                    )), html_writer::empty_tag('img', array(
                        'src' => $imageurl,
                        'alt' => 'delete', 'class' => 'iconsmall delete'
                    )), array('title' => 'delete'));
                }
                $this->content->text .= html_writer::start_tag('li');
                $this->content->text .= html_writer::link($recordinglist->recordinglink, $recordinglist->recordingname);
                $this->content->text .= html_writer::end_tag('li');
                if ($DB->get_record('role', array('shortname' => 'editingteacher'), '*', MUST_EXIST)) {
                    $this->content->text .= $buttons;
                }
            }
            $this->content->text .= html_writer::end_tag('ul');
        }
        return $this->content;
    }
    public function instance_allow_config() {
        return true;
    }

    public function instance_config_save($data, $nolongerused = false) {
        if (get_config('sharerecording', 'Allow_HTML') == '1') {
            $data->text = strip_tags($data->text);
        }
        return parent::instance_config_save($data, $nolongerused);
    }
}