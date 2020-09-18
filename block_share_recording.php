<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Share recording block.
 *
 * @package   block_share_recording
 */

defined('MOODLE_INTERNAL') || die();

class block_share_recording extends block_base {
    function init() {
        $this->title = get_string('pluginname', 'block_share_recording');
    }
    
    function applicable_formats() {
        return array(
                 'site-index' => true,
                     'course' => true,
         'course-view-social' => false,
                        'mod' => false,
                        'my'  => false,
                   'mod-quiz' => false
        );
    }

    function has_config()
    {
        return true;
    }

    function get_content() {
        global $DB, $CFG, $COURSE;
        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->footer = '';
        $this->content->text   = '';

        if (empty($this->config)) {
            $this->config = new stdClass();
        }

        if (empty($this->instance)) {
            return $this->content;
        }
        if ($recordinglists = $DB->get_records('block_share_recording')) {
            $this->content->text .= html_writer::start_tag('ul');
            foreach ($recordinglists as $recordinglist) {
                $deleteparam = array('id' => $recordinglist->id, 'courseid' => $COURSE->id);
                $deleteurl = new moodle_url('/blocks/share_recording/delete.php', $deleteparam);
                if (has_capability('block/sharerecording:addinstance', context_course::instance($COURSE->id))) {
                    $imageurl = "$CFG->wwwroot/blocks/share_recording/pix/delete.png";
                    $buttons = html_writer::link(new moodle_url($deleteurl, array(
                        'deleterecording' => $recordinglist->sessionid,
                        'recordingname' => $recordinglist->recordingname
                    )), html_writer::empty_tag('img', array(
                        'src' => $imageurl,
                        'width' => '16px',
                        'alt' => 'delete', 'class' => 'iconsmall delete mx-2'
                    )), array('title' => 'delete'));
                }
                $this->content->text .= html_writer::start_tag('li');
                $this->content->text .= html_writer::link($recordinglist->recordinglink, $recordinglist->recordingname, array('target' => '_blank'));
                if (has_capability('block/sharerecording:addinstance', context_course::instance($COURSE->id))) {    
                    $this->content->text .= $buttons;
                }
            }
            $this->content->text .= html_writer::end_tag('li');
            $this->content->text .= html_writer::end_tag('ul');
        }
        return $this->content;
    }
    function instance_allow_config() {
        return true;
    }
    function instance_allow_multiple() {
        return true;
    }

    function instance_config_save($data, $nolongerused = false) {
        if (get_config('sharerecording', 'Allow_HTML') == '1') {
            $data->text = strip_tags($data->text);
        }
        return parent::instance_config_save($data, $nolongerused);
    }
}