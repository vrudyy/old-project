<?PHP
require_once($_SERVER['DOCUMENT_ROOT']."/main/dictionary/Dictionary2.php");


class Dictionary{
    
    private $words = [];
    private $languages = ["english", "russian", "greek"];
    private $dic;
    
    function __construct($lang = "english") {
        $lang = strtolower($lang);
        if(in_array($lang, $this->languages) == true){
            $this->$lang();
        }else{
            $this->english();
        }
        $this->dic = new Dictionary2($lang);
        
    }
    
    private function english(){
        $this->words["overview"] = "Overview";
        $this->words["settings"] = "Settings";
        $this->words["welcome back,"] = "Welcome back,";
        $this->words["not found"] = "not found!";
    }
    
    private function russian(){
        $this->words["overview"] = "Oбзор";
        $this->words["users"] = "Юзеры";
        $this->words["user details"] = "Информация о пользователе";
        $this->words["first name"] = "имя";
        $this->words["last name"] = "фамилия";
        $this->words["add user"] = "Добавить";  
        $this->words["update user"] = "Обновить пользователя";
        $this->words["update"] = "Обновить"; 
        $this->words["new user"] = "Новый пользователь";
        $this->words["name"] = "имя";
        $this->words["role"] = "роль";
        $this->words["email"] = "эл. адрес";
        $this->words["phone"] = "телефон";
        $this->words["upload"] = "Загрузить";
        $this->words["make it large (e.g. 1000px wide) and either png, gif or jpg format."] = "Сделайте его большим (например, шириной 1000 пикселей) и PNG, GIF или JPG.";
        $this->words["current logo"] = "Текущий логотип";
        $this->words["company name"] = "Hазвание компании";
        $this->words["company address"] = "Адрес компании";
        $this->words["town"] = "Город";
        $this->words["region or state"] = "Регион или государство";
        $this->words["post/zip code"] = "Почтовый индекс";
        $this->words["language"] = "Язык";
        $this->words["contact email address"] = "Контактный адрес электронной почты";
        $this->words["contact phone number"] = "Контактный номер телефона";
        $this->words["other details"] = "Другие детали";
        $this->words["company registration number"] = "Регистрационный номер компании";
        $this->words["vat number"] = "Номер НДС";
        $this->words["save"] = "Сохранить";
        $this->words["cancel"] = "Отмена";
        $this->words["log out"] = "Выйти";
        $this->words["settings"] = "Hастройки";
        $this->words["welcome back,"] = "Добро пожаловать,";
        $this->words["my company"] = "Моя компания";
        $this->words["company details"] = "Сведения о компании";
        $this->words["company logo"] = "Логотип компании";
        $this->words["edit your address details, contact and other company information."] = "Измените данные своего адреса, контактную информацию и другую информацию о компании.";
        $this->words["upload or change your company logo."] = "Загрузите или измените логотип своей компании.";
        $this->words["add and manage users, passwords and control user access levels."] = "Добавление и управление пользователями, паролями и контроль уровня доступа пользователей.";
        $this->words["not found"] = "Hе найдено!";
        $this->words["remove"] = "Удалить";
        $this->words["deactivate"] = "Деактивировать";
    }


    private function greek(){
        $this->words["overview"] = "Ανασκόπηση";
        $this->words["users"] = "Χρήστες";
        $this->words["user details"] = "Λεπτομέρειες χρήστη";
        $this->words["first name"] = "Όνομα";
        $this->words["last name"] = "Επίθετο";
        $this->words["add user"] = "Προσθήκη χρήστη";  
        $this->words["update user"] = "Ενημέρωση χρήστη";
        $this->words["update"] = "Αποθήκευση"; 
        $this->words["new user"] = "Νέος χρήστης";
        $this->words["name"] = "Όνομα";
        $this->words["role"] = "Ρόλος";
        $this->words["email"] = "Ε-mail";
        $this->words["phone"] = "Τηλέφωνο";
        $this->words["upload"] = "Μεταφόρτωση";
        $this->words["make it large (e.g. 1000px wide) and either png, gif or jpg format."] = "Χρησιμοποιείστε μεγάλη εικόνα (π.χ πλάτος 1000 px) και σε αρχείο τύπου png, gif ή jpg";
        $this->words["current logo"] = "Υπάρχον λογότυπος";
        $this->words["company name"] = "Όνομα εταιρείας";
        $this->words["company address"] = "Διεύθυνση εταιρείας";
        $this->words["town"] = "Πόλη";
        $this->words["region or state"] = "Περιοχή";
        $this->words["post/zip code"] = "Ταχ. κώδικας";
        $this->words["language"] = "Γλώσσα";
        $this->words["contact email address"] = "E-mail επικοινωνίας";
        $this->words["contact phone number"] = "Τηλέφωνο επικοινωνίας";
        $this->words["other details"] = "Άλλα στοιχεία";
        $this->words["company registration number"] = "Αριθμός μητρώου εταιρείας";
        $this->words["vat number"] = "Αριθμός ΦΠΑ";
        $this->words["save"] = "Αποθήκευση";
        $this->words["cancel"] = "Ακύρωση";
        $this->words["log out"] = "Έξοδος";
        $this->words["settings"] = "Ρυθμίσεις";
        $this->words["welcome back,"] = "Καλώς ήρθες,";
        $this->words["my company"] = "Η εταιρεία";
        $this->words["company details"] = "Στοιχεία εταιρείας";
        $this->words["company logo"] = "Λογότυπος εταιρείας";
        $this->words["edit your address details, contact and other company information."] = "Επεξεργασία της ταχυδρομικής διεύθυνσης, τηλεφώνων και άλλα στοιχεία της εταιρείας.";
        $this->words["upload or change your company logo."] = "Μεταφόρτωση ή αλλαγή του λογότυπου της εταιρείας.";
        $this->words["add and manage users, passwords and control user access levels."] = "Προσθήκη και διαχείρηση χρηστών, κωδικών πρόσβασης και ρύθμιση βαθμού πρόσβασης.";
        $this->words["not found"] = "Δεν βρέθηκε";
        $this->words["remove"] = "Αφαιρώ";
    }
    
    public function translate($word){
        return $this->dic->translate($word);
    }

       
}
