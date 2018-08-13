<?PHP
require_once($_SERVER['DOCUMENT_ROOT']."/main/dictionary/languages/Russian.php");
require_once($_SERVER['DOCUMENT_ROOT']."/main/dictionary/languages/Greek.php");

class Dictionary2{ 
    
    private $words = [];
    private $languages = ["russian", "greek"];
    
    
    function __construct($lang = "") {
        $lang = strtolower($lang);
        if(in_array($lang, $this->languages) == true){
            $dic = new $lang;
            $this->words = $dic->getWords();
        }
        
    }
    
    public function translate($word){
        return (array_key_exists(strtolower($word), $this->words)) ? $this->words[strtolower($word)] : $word;
    }

       
}
