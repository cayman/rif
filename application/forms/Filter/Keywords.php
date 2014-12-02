<?php
class Site_Form_Filter_Keywords implements Zend_Filter_Interface
{
    /**
     * Массив стоп-символов, которые будут удаленны из строки
     *
     * @var array
     */
    protected $_stopSymbols = array(/*"x27","x22","x60",*/"\t","\n","\r",'…',
                                    '–','-','\\','\'','|','`', //Здесь минусы в разных кодировках
                                    ',','.',';',':','?','!',
                                    ')','(','[',']','{','}','"','«','»','<','>',
                                    '=','+','*','/','%',
                                    '&','@','~','^','$','#');
    /**
     * Массив стоп-слов, которые будут удаленны из строки
     *
     * @var array
     */

    protected $_stopWords = array();
    private $stopWords = 'todo name link note node list code quote size url
 без более будто бы был была были было быть
 вам вас весь во вот все всего всем всех всю вы где да даже для до его ее если  есть ещё еще
 же за здесь из из-за или им их как как-то ко кого когда коль кому кто ли либо
 меня мне может мы моя мое на над нам нас наш наша не него неё ней нет ни них но ну
 об однако он она они оно от очень по под пока при сам себе со свой
 так также такой там твое твой твоя те тебе тебя тем тех то того тоже той только том тот ты
 уж уже хотя хоть чего чей через чем что чтоб чтобы чьё чья эта эти это этом этого';
    /**
     * Лимит ключевых слов (если 0 значит не лимитированно)
     *
     * @var integer
     */

    protected $_limit = 0;

    /**
     * Разделитель ключевых слов
     *
     * @var string|null
     */

    protected $_separator = ',';

    /**
     * Конструктор класса
     *
     * @param integer $limit лимит ключевых слов
     * @param string|null $separator разделитель ключевых слов
     */

    public function __construct($limit = 20)
    {
        $this->_limit       = intval($limit);
        $this->_stopWords   = explode(' ', strval($this->stopWords));

    }

    /**
     * Основной метод фильтрующий строку
     *
     * @param string $value строка для обработки
     * @return string результат
     */

    public function filter($value)
    {
        $keywords = array();
        Log::debug($this,'filter-0',$value);
        $value    = str_replace($this->_stopSymbols, ' ', $value);
        Log::debug($this,'filter-1',$value);
        Log::info($this,print_r($this->_stopWords,true));
        foreach (explode(" ", $value) as $word) {
            $word=trim($word);
            $count=mb_strlen($word);
            $lowWord=mb_strtolower($word);
            Log::info($this,"$lowWord($count)");
            if ($count<2 || in_array($lowWord, $this->_stopWords)) {
               Log::info($this,"$word ($lowWord): NO");
            }else{
                $keywords[] = $word;
                Log::info($this,"$word ($lowWord): YES");
            }



        }
        Log::info($this,'filter-2',$keywords);
        $keywords = array_count_values($keywords);
        //arsort($keywords);
        //Log::debug($this,'filter-3',$keywords);
        $keywords = array_keys($keywords);
        Log::debug($this,'filter-4',$keywords);
        if ($this->_limit) {
            $keywords = array_slice($keywords, 0, $this->_limit);
        }
        Log::debug($this,'filter-5',$keywords);
        return join($this->_separator . ' ', $keywords);
    }
}
