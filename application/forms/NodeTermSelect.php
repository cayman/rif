<?php
/**
 * Created by IntelliJ IDEA.
 * User: Zakirov
 * Date: 25.03.2010
 * Time: 14:05:25
 * To change this template use File | Settings | File Templates.
 */

class Site_Form_NodeTermSelect extends Zend_Form_SubForm {

    private $classifier;
    private $terms;


    public function loadDefaultDecorators() {
        $this->addDecorator('FormElements');
    }

    public function __construct(Site_Model_Classifier $classifier) {
        $this->classifier = $classifier;
        $this->terms = Site_Service_Registry::get(TAXONOMY)->getTerms($classifier);
        parent::__construct();
    }

    public function init() {
        $term_id = new Site_Form_Element_NodeTermSelect(ID,
            $this->classifier->name, $this->terms, $this->classifier->term_required);
        $term_id->setValue($this->classifier->term_default);
        $this->addElement($term_id);

        if ($this->classifier->value_type == Site_Model_Term::DECIMAL_VALUE) {
            $term_value = new Site_Form_Element_CommonSpinner(VALUE, $this->classifier->value_name);
            $term_value->addDecorator('Label', array('class' => 'inline'));
            $term_value->removeDecorator('HtmlTag');
            $term_id->addDecorator('HtmlTag', array('tag' => 'dd', 'class' => 'inline'));
            $this->addElement($term_value);
        } else if ($this->classifier->value_type == Site_Model_Term::CHAR_VALUE) {
            $term_value = new Site_Form_Element_NodeTermValue(VALUE, $this->classifier->value_name);
            $term_value->addDecorator('Label', array('class' => 'inline'));
            $term_value->removeDecorator('HtmlTag');
            $term_id->addDecorator('HtmlTag', array('tag' => 'dd', 'class' => 'inline'));
            $this->addElement($term_value);
        }

    }


    public function getTerms() {
        $terms = array();
        $id = $this->getValue(ID);
        if (!empty($id)) {
            $term = new Site_Model_Term();
            $term->id = $id;
            $term->term_value = $this->getValue(VALUE);
            $term->class_id = $this->classifier->id;
            $terms[] = $term;
        }
        return $terms;
    }

    public function populate(array $terms) {
        foreach ($terms as $term) {
            //var_dump($term->id . ":" . $term->name);
            Log::info($this, "populate {$term->id}");
            $this->setDefaults(array(
                ID => $term->id,
                VALUE => $term->term_value,
            ));
        }

    }

}
