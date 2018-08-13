<?php
require_once($_SERVER['DOCUMENT_ROOT']."/main/dictionary/Lang.php");


class Russian extends Lang{
    
    protected function setWords() {
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

}

