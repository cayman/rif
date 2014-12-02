<?php

class Site_Model_DbTable_Verse extends Zend_Db_Table_Abstract
{
    protected $_name    = 'verse';
    protected $_primary = 'id';

    /**
     * Получить стихи одной главы
     * содержащее ключевое слово
     * @param int $headId Номер главы
     * @param string $key Ключевое слово
     * @return resultSet
     */
    public function findBy($headId,$key,$order,$hide=null)
    {
        return $this->fetchAll($this->selectBy($headId,$key,$order,$hide));
    }

    public function countBy($params)
    {
        $select = $this->select()->setIntegrityCheck(false)
                ->from($this->_name,'COUNT(*) AS num');
        if(isset($params[HEAD]))
           $select = $select->where('verse.head = ?',$params[HEAD]);
        if(isset($params[KEY]))
           $select = $select->where('verse.verse like ?',"%{$params[KEY]}%");
        if(isset($params[SOURCE]))
           $select = $select->where('verse.source = ?',$params[SOURCE]);
        if(isset($params[HIDE]))
            $select=$select->where('verse.hide = ?',$params[HIDE]);
        if(isset($params[RANK]))
            $select=$select->where('verse.rank <> 0');
        Log::info($this,'countBy',$select);
        return $this->fetchRow($select)->num;
    }

    public function selectBy($params,$order)
    {
        $select = $this->select()->setIntegrityCheck(false)
                ->from($this->_name,'verse.*')
                ->joinLeft('comment','comment.verse=verse.id AND comment.hide = 0',
                            array('COUNT(comment.id) AS ccount'))
                ->joinLeft('verse_tags','verse_tags.verse=verse.id',
                            array('GROUP_CONCAT(verse_tags.tag) AS tags'))
                ->group('verse.id');
        if(isset($order))
           $select = $select->order("verse.$order DESC");
        if(isset($params[HEAD]))
           $select = $select->where('verse.head = ?',$params[HEAD]);
        if(isset($params[KEY]))
           $select = $select->where('verse.verse like ?',"%{$params[KEY]}%");
        if(isset($params[SOURCE]))
           $select = $select->where('verse.source = ?',$params[SOURCE]);
        if(isset($params[HIDE]))
            $select=$select->where('verse.hide = ?',$params[HIDE]);
        if(isset($params[RANK]))
            $select=$select->where('verse.rank <> 0');
        Log::info($this,'selectBy',$select);
        return $select;
    }
    /*
    SELECT `verse`.*, COUNT(comment.id) AS `ccount`, GROUP_CONCAT(verse_tags.tag) AS `tags` FROM `verse`
LEFT JOIN `comment` ON comment.verse=verse.id AND comment.hide = 0
LEFT JOIN `verse_tags` ON verse_tags.verse=verse.id WHERE (verse.head = '3')
GROUP BY `verse`.`id` ORDER BY `verse`.`date` DESC
     *
     */
        /**
     * Получить стихи одной темы
     * содержащее ключевое слово
     * @param int $headId Номер главы
     * @param string $key Ключевое слово
     * @return resultSet
     */
    public function findByTag($tag,$order,$hide=null)
    {
        return $this->fetchAll($this->selectByTag($tag,$order,$hide=null));
    }

    public function countByTag($tag,$hide=null)
    {
        $select = $this->select()->setIntegrityCheck(false)
                ->from('verse_tags','COUNT(*) AS num')
                ->joinLeft($this->_name,'verse.id=verse_tags.verse',array())
                ->where('verse_tags.tag = ?',$tag);
        if(isset($hide))
            $select=$select->where('verse.hide = ?',$hide);
        Log::debug($this,'countBy',$select);
        return $this->fetchRow($select)->num;
    }

/*    SELECT COUNT(*) AS `num`
FROM `verse_tags`
LEFT JOIN `verse` ON verse.id=verse_tags.verse
WHERE (verse.hide <= 0) and (verse_tags.tag = '1')
*/
    public function selectByTag($tag,$order,$hide=null)
    {
        $select = $this->select()->setIntegrityCheck(false)
                ->from('verse_tags')
                ->joinLeft($this->_name,'verse.id=verse_tags.verse',array('verse.*'))
                ->joinLeft('verse_tags AS vtags','vtags.verse=verse.id',
                            array('GROUP_CONCAT(vtags.tag) AS tags'))
                ->where('verse_tags.tag = ?',$tag)
                ->group('verse.id');
        if(isset($hide))
            $select=$select->where('verse.hide = ?',$hide);
        if(isset($order))
           $select = $select->order("verse.$order DESC");
        Log::debug($this,'selectByTag',$select);
        return $select;
    }
    /*
SELECT verse . * , GROUP_CONCAT( tg.tag ) AS tags
FROM `verse_tags`
LEFT JOIN `verse` ON verse.id = verse_tags.verse
LEFT JOIN verse_tags AS tg ON tg.verse = verse.id
WHERE (
verse.hide <=0
)
AND (
verse_tags.tag = '1'
)
GROUP BY verse.id
LIMIT 0 , 30
     */
    /**
     * Получить стихи для главной стрницы
     * @param array $head Номера глав
     * @param int $count Количество для каждой главы
     * @return resultSet
     */
    public function findLastByHeads($heads,$count)
    {   $select_parts=array();
        foreach($heads as $headId){
            $select = $this->select()
                ->where("verse.head = ?",$headId)
                ->where('verse.hide = ?',0)
                ->order("date DESC")
                ->limit($count);
            Log::debug($this,$select->__toString());
            $select_parts[]='('.$select->__toString().')';
        }
        $select = $this->select()
                ->union($select_parts)
                ->order("date DESC");
        Log::debug($this,$select->__toString());
        return $this->fetchAll($select);
    }

    /**
     * Поиск стихов идентичных заданному
     * @param array $key слова поиска
     * @param int $verseId заданный стих
     * @return resultSet
     */
    public function findByKeys($keys,$verseId=null,$hide=null)
    {
        Log::debug($this,'findByKeys',$keys);
        $select_parts=array();
        foreach ($keys as $index=>$key ){
            Log::debug($this,"$index=>$key");
            $select = $this->select()->setIntegrityCheck(false)
                ->from($this->_name,array('num'=>new Zend_Db_Expr($index),'verse.*'))
                ->where('verse.verse like ?',"%$key%");
            if(isset($hide))
                $select->where('verse.hide <= ?',$hide);
            $select_parts[]=$select;
        }
        $unionSelect = $this->select()->union($select_parts,Zend_Db_Select::SQL_UNION_ALL);
        Log::debug($this,$unionSelect->__toString());
        $subSelect = $this->select()->setIntegrityCheck(false)
            ->from(array('tbl' => $unionSelect),array('ccount'=>'count(num)','tbl.*'))
            ->group('id')
            ->having('ccount > ?',count($keys)-2)
            ->order('ccount DESC');
        if(isset($verseId)) $select->where('tbl.id != ?',$verseId);
        $subSelect->assemble();
        Log::debug($this,$subSelect->__toString());
        return $this->fetchAll($subSelect);
   }

       public function findSource($length=1)
    {
        $select = $this->select()->setIntegrityCheck(false)
                ->from('verse',array('source','ccount'=>'count(source)'))
                ->where("length(source) >= ?",$length)
                ->group('source');
                //->order("source");
         Log::debug($this,$select->__toString());
        return $this->fetchAll($select);
    }

    public function findCountByHide()
    {
        $select = $this->select()->setIntegrityCheck(false)
                ->from('verse',array('count'=>'COUNT(id)','hide'))
                ->group('hide');
        Log::debug($this,'CountByHide',$select);
        return $this->fetchAll($select);
    }


    public function insert($model)
    {
        Log::info($this,"insert",$model);
        $sql_date =$model->date->toString('YYYY-MM-dd HH:mm:ss');
        $data = array(
                'verse.head' => $model->head,
                'verse.verse' => $model->verse,
                'verse.desc' => $model->desc,
                'verse.key' => $model->key,
                'verse.source' => $model->source,
				'verse.page' => $model->page,
                'verse.hide' => $model->hide,
                'verse.date' => $sql_date);
        Log::debug($this,"insert",$data);
        return parent::insert($data);
    }

    public function update($model)
    {
        Log::info($this,"update",$model);
        $sql_date =$model->date->toString('YYYY-MM-dd HH:mm:ss');
        $data = array(
                'verse.head' => $model->head,
                'verse.verse' => $model->verse,
                'verse.desc' => $model->desc,
                'verse.key' => $model->key,
                'verse.source' => $model->source,
				'verse.page' => $model->page,
                'verse.hide' => $model->hide,
                'verse.date' => $sql_date);
        Log::debug($this,"update",$data);
        parent::update($data,"verse.id = {$model->id}");
    }

    public function delete($model)
    {
        $data = array(
                'verse.hide' => 2,
                );
        Log::info($this,"delete",$model);
        parent::update($data,"verse.id = {$model->id}");
    }

    public function up($model)
    {
        Log::info($this,"up rank",$model);
        $data = array('verse.rank' => new Zend_Db_Expr('verse.rank+1'));
        Log::debug($this,"update",$data);
        parent::update($data,"verse.id = {$model->id}");
    }

    public function down($model)
    {
        Log::info($this,"up rank",$model);
        $data = array('verse.rank' => new Zend_Db_Expr('verse.rank-1'));
        Log::debug($this,"update",$data);
        parent::update($data,"verse.id = {$model->id}");
    }
}
