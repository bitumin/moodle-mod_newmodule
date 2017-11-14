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

namespace mod_newmodule\form;

use mod_newmodule\api;
use moodleform;

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once($CFG->libdir . '/formslib.php');
require_once($CFG->dirroot . '/repository/lib.php');

/**
 * Class collaborativetype_gallery2_dropzone_form
 */
class filepicker extends moodleform {
    /**
     * filepicker constructor.
     */
    public function __construct() {
        parent::__construct('javascript:');
    }

    /**
     * @throws \HTML_QuickForm_Error
     */
    protected function definition() {
        $form = $this->_form;
        $form->addElement('filepicker', 'attachment', get_string('file'), null, $this->get_filepicker_options());
    }

    /**
     * @return array
     */
    private function get_filepicker_options() {
        return array(
            'maxfiles' => 1,
            'subdirs' => false,
            'maxbytes' => api::get_maxbytes_per_file(10485760),
            'accepted_types' => array('image'),
            'return_types' => FILE_INTERNAL | FILE_EXTERNAL,
        );
    }
}
