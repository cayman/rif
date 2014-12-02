<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Zakirov
 * Date: 27.09.2010
 * Time: 16:51:27
 * To change this template use File | Settings | File Templates.
 */

class Site_Model_Comment extends Site_Model_Entity {
    const SPAM=4;

    protected $_node_id;
    protected $_name;
    protected $_text;
    protected $_level;
    protected $_level_name;
    protected $_level_desc;
    protected $_date;
    protected $_ip;

}
