<?php

namespace local_vmoodle;

require_once($CFG->libdir.'/formslib.php');

/**
 * Define form to choose targets.
 * 
 * @package local_vmoodle
 * @category local
 * @author Bruce Bujon (bruce.bujon@gmail.com)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL
 */
class Target_Form extends \moodleform {
    /**
     * Constructor.
     * @param $customdata array The data about the form such as available platforms (optional).    
     */
    public function __construct($customdata = null) {
        parent::__construct(new moodle_url('/local/vmoodle/view.php'), $customdata, 'post', '', array('onsubmit'=>'submit_target_form()'));
    }
    
    /**
     * Describes form.
     */
    public function definition() {
        global $CFG;
        
        // Setting variables
        $mform =& $this->_form;

        // Define available targets
        if (isset($this->_customdata['aplatforms'])) {
            $achoices = $this->_customdata['aplatforms'];
            if (empty($achoices)) {
                $achoices = array(get_string('none', 'local_vmoodle'));
            }
        } else {
            $achoices = get_available_platforms();
        }

        // Define selected targets
        if (isset($this->_customdata['splatforms']) && !empty($this->_customdata['splatforms'])) {
            $schoices = $this->_customdata['splatforms'];
        } else {
            $schoices = array(get_string('none', 'local_vmoodle'));
        }

        // Adding header.
        $mform->addElement('header', 'platformschoice', get_string('virtualplatforms', 'local_vmoodle'));

        // Adding hidden field.
        $mform->addElement('hidden', 'view', 'sadmin');
        $mform->setType('view', PARAM_TEXT);

        $mform->addElement('hidden', 'what', 'sendcommand');
        $mform->setType('what', PARAM_TEXT);

        $mform->addElement('hidden', 'achoices', json_encode($achoices));
        $mform->setType('achoices', PARAM_TEXT);

        // Adding selects group.
        $selectarray = array();
        $selectarray[0] = &$mform->createElement('select', 'aplatforms', get_string('available', 'local_vmoodle'), $achoices, 'size="15"');
        $selectarray[1] = &$mform->createElement('select', 'splatforms', get_string('selected', 'local_vmoodle'), $schoices, 'size="15"');
        $selectarray[0]->setMultiple(true);
        $selectarray[1]->setMultiple(true);
        $mform->addGroup($selectarray, 'platformsgroup', null, ' ', false);

        // Adding platforms buttons group.
        $buttonarray = array();
        $buttonarray[] = &$mform->createElement('button', null, get_string('addall', 'local_vmoodle'), 'onclick="select_all_platforms(); return false;"');
        $buttonarray[] = &$mform->createElement('button', null, get_string('addtoselection', 'local_vmoodle'), 'onclick="select_platforms(); return false;"');
        $buttonarray[] = &$mform->createElement('button', null, get_string('removefromselection', 'local_vmoodle'), 'onclick="unselect_platforms(); return false;"');
        $buttonarray[] = &$mform->createElement('button', null, get_string('removeall', 'local_vmoodle'), 'onclick="unselect_all_platforms(); return false;"');
        $mform->addGroup($buttonarray);

        // Adding submit buttons group.
        $buttonarray = array();
        $buttonarray[] = $mform->createElement('submit', 'submitbutton', get_string('nextstep', 'local_vmoodle'));
        $buttonarray[] = $mform->createElement('cancel', 'cancelbutton', get_string('cancelcommand', 'local_vmoodle'));
        $mform->addGroup($buttonarray);

        // Changing renderer.
        $renderer =& $mform->defaultRenderer();
        $template = '<label class="qflabel" style="vertical-align:top">{label}</label> {element}';
        $renderer->setGroupElementTemplate($template, 'platformsgroup');
    }
}