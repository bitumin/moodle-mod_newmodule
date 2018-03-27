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
 * Class for gallery persistence.
 *
 * @package    mod_gallery
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_newmodule\persistent;

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/../../locallib.php');

use cm_info;
use context_module;
use core\persistent;
use core_user;
use lang_string;
use stdClass;

/**
 * Class for loading/storing galleries from the DB.
 *
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class exmaple extends persistent {
    const TABLE = 'newmodule';

    /**
     * Return the definition of the properties of this model.
     *
     * @return array
     */
    protected static function define_properties() {
        return array(
            'course' => array(
                'type' => PARAM_INT,
            ),
            'userid' => array(
                'type' => PARAM_INT,
            ),
            'name' => array(
                'type' => PARAM_TEXT,
            ),
            'intro' => array(
                'type' => PARAM_RAW,
                'optional' => true,
                'null' => NULL_ALLOWED,
                'description' => 'Gallery module instance introduction text',
                'default' => null,
            ),
            'introformat' => array(
                'type' => PARAM_INT,
                'choices' => array(
                    FORMAT_MOODLE,
                    FORMAT_HTML,
                    FORMAT_PLAIN,
                    FORMAT_MARKDOWN
                ),
                'default' => FORMAT_MOODLE
            ),
        );
    }

    /*
     * Extra properties validation
     */

    /**
     * @param $value
     * @return true|lang_string
     * @throws \dml_exception
     * @throws \coding_exception
     */
    protected function validate_course($value) {
        global $DB;
        if (!$DB->record_exists('course', ['id' => $value])) {
            return new lang_string('invalidcourseid', 'error');
        }

        return true;
    }

    /**
     * @param $value
     * @return true|lang_string
     * @throws \coding_exception
     */
    protected function validate_userid($value) {
        if (!core_user::is_real_user($value, true)) {
            return new lang_string('invaliduserid', 'error');
        }

        return true;
    }

    /*
     * Custom property getters
     */

    public function get_formatted_name() {
        return format_string($this->get('name'));
    }

    /*
     * Relationships helpers
     */

    /**
     * @return bool|stdClass
     * @throws \dml_exception
     */
    public function get_author() {
        return core_user::get_user($this->get('userid'));
    }

    /**
     * @return bool|stdClass
     * @throws \dml_exception
     */
    public function get_last_modification_author() {
        return core_user::get_user($this->get('usermodified'));
    }

    /**
     * @return stdClass
     */
    public function get_course_record() {
        return get_course($this->get('course'));
    }

    /**
     * @return false|stdClass
     */
    public function get_cm() {
        $instanceid = $this->get('id');
        $courseid = $this->get('course');

        // Try using fast modinfo first (uses cache).
        $modinfo = get_fast_modinfo($courseid);
        if (null !== $modinfo && isset($modinfo->instances['gallery'][$instanceid])) {
            /** @var cm_info $cminfo */
            $cminfo = $modinfo->instances['gallery'][$instanceid];
            return $cminfo->get_course_module_record();
        }

        // Default to helper (uses db query).
        if ($cm = get_coursemodule_from_instance('gallery', $instanceid, $courseid)) {
            return $cm;
        }

        return false;
    }

    /**
     * @return false|context_module
     */
    public function get_context() {
        if (!$cm = $this->get_cm()) {
            return false;
        }

        return context_module::instance($cm->id);
    }

    /*
     * Permission checkers
     */

    public function can_edit($context, $userid = null) {
        global $USER;
        if (null === $userid) {
            $userid = $USER->id;
        }

        return has_capability('mod/gallery:edit', $context, $userid);
    }
}
