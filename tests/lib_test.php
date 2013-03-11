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
 * Adaptive lib.php PHPUnit tests
 *
 * @package    mod_adaptivequiz
 * @category   phpunit
 * @copyright  2013 onwards Remote-Learner {@link http://www.remote-learner.ca/}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot.'/mod/adaptivequiz/lib.php');

class mod_adaptivequiz_lib_testcase extends advanced_testcase {
    /**
     * Provide input data to the parameters of the test_questioncat_association_insert() method.
     */
    public function questioncat_association_records() {
        $data = array();

        $adaptivequiz = new stdClass();
        $adaptivequiz->questionpool = array(1, 2, 3, 4);
        $data[] = array(1, $adaptivequiz);

        $adaptivequiz = new stdClass();
        $adaptivequiz->questionpool = array(1, 2);
        $data[] = array(2, $adaptivequiz);

        $adaptivequiz = new stdClass();
        $adaptivequiz->questionpool = array(1, 2, 4);
        $data[] = array(3, $adaptivequiz);

        return $data;
   }

    /**
     * Test insertion of question category association records
     * @dataProvider questioncat_association_records
     * @param int $instance: activity instance id
     * @param object $adaptivequiz: An object from the form in mod_form.php
     * @group adaptivequiz_lib_test
     */
    public function test_questioncat_association_insert($instance, stdClass $adaptivequiz) {
        global $DB;

        $this->resetAfterTest(true);

        adaptivequiz_add_questcat_association($instance, $adaptivequiz);

        if (1 == $instance) {
            $this->assertEquals(4, $DB->count_records('adaptivequiz_question', array('instance' => $instance)));
        }

        if (2 == $instance) {
            $this->assertEquals(2, $DB->count_records('adaptivequiz_question', array('instance' => $instance)));
        }

        if (3 == $instance) {
            $this->assertEquals(3, $DB->count_records('adaptivequiz_question', array('instance' => $instance)));
        }
    }

    /**
     * Test update of question category associations records
     * @dataProvider questioncat_association_records
     * @param int $instance: activity instance id
     * @param object $adaptivequiz: An object from the form in mod_form.php
     * @group adaptivequiz_lib_test
     */
    public function test_questioncat_association_update($instance, stdClass $adaptivequiz) {
        global $DB;

        $this->resetAfterTest(true);

        adaptivequiz_add_questcat_association($instance, $adaptivequiz);

        // Test 
        if (1 == $instance) {
            $adaptivequizupdate = new stdClass();
            $adaptivequizupdate->questionpool = array(111, 222, 333, 444, 555, 122, 133, 144, 155, 166);

            adaptivequiz_update_questcat_association($instance, $adaptivequizupdate);
            $this->assertEquals(10, $DB->count_records('adaptivequiz_question', array('instance' => $instance)));
        }

        if (2 == $instance) {
            $adaptivequizupdate = new stdClass();
            $adaptivequizupdate->questionpool = array(4);

            adaptivequiz_update_questcat_association($instance, $adaptivequizupdate);
            $this->assertEquals(1, $DB->count_records('adaptivequiz_question', array('instance' => $instance)));
        }

        if (3 == $instance) {
            $adaptivequizupdate = new stdClass();
            $adaptivequizupdate->questionpool = array(4, 10, 20, 30, 40, 100, 333);

            adaptivequiz_update_questcat_association($instance, $adaptivequizupdate);
            $this->assertEquals(7, $DB->count_records('adaptivequiz_question', array('instance' => $instance)));
        }
    }
}
