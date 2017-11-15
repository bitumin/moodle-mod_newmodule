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

namespace mod_newmodule;

defined('MOODLE_INTERNAL') || die;

use core\output\inplace_editable as core_inplace_editable;
use lang_string;
use renderer_base;

/**
 * Class mod_gallery_inplace_editable
 */
class inplace_editable extends core_inplace_editable {
    public function __construct($itemtype, $itemid, $value, $editable) {
        $displayvalue = format_string($value);
        $hint = new lang_string('inplace_hint', 'mod_gallery');
        $editlabel = new lang_string('editlabel', 'mod_gallery') . ' ' . format_string($value);

        parent::__construct('mod_gallery', $itemtype, $itemid, $editable, $displayvalue, $value, $hint, $editlabel);
    }

    /**
     * Renders this element
     *
     * @param renderer_base $output typically, the renderer that's calling this function
     * @return string
     */
    public function render(renderer_base $output) {
        return $output->render_from_template('mod_newmodule/inplace_editable', $this->export_for_template($output));
    }
}
