<?php
/**
 * Created by IntelliJ IDEA.
 * User: Zakirov
 * Date: 25.03.2010
 * Time: 14:05:25
 * To change this template use File | Settings | File Templates.
 */

class Site_Form_NodeTermAutocomplete extends Zend_Form_SubForm {

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
        $element_name = new Site_Form_Element_NodeTermAutoComplete(NAME,
            $this->classifier->name, $this->terms, $this->classifier->term_required);
        $this->addElement($element_name);
    }


    public function getTerms() {
        $term_names = array_filter(array_map('trim', explode(',', $this->getValue(NAME))));
        $terms = array();
        foreach ($term_names as $term_name) {
            $term = new Site_Model_Term();
            $term->name = $term_name;
            $term->class_id = $this->classifier->id;
            $terms[] = $term;
        }
        return $terms;
    }


    public function populate(array $terms) {
        $terms_setup = array(); //for populate
        foreach ($terms as $term) {
            //var_dump($term->id . ":" . $term->name);
            //var_dump($term);
            $terms_setup[$term->id] = $term->name;
        }
        $this->setDefaults(array(
            NAME => implode(', ', $terms_setup)
        ));
    }

}
