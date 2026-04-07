<?php
    header('Content-Type: text/html; charset=UTF-8');

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (!empty($_GET['save'])) {
            print('Спасибо, результаты сохранены.');
            // Очищаем cookies с ошибками после успешного сохранения
            setcookie('errors_array', '', time() - 3600, '/');
        }
        include('form.php');
        exit();
    }
    
    $errors_array = [];

    if (empty($_POST['fio'])) {
        $errors_array['fio'] = "Заполните имя.";
    }
    elseif (strlen($_POST['fio'])>150) {
        $errors_array['fio'] = "Слишком много символов в поле ФИО.";
    }
    elseif (!preg_match('/^[a-zA-Zа-яА-ЯёЁ\s-]+$/u', $_POST['fio'])) {
        $errors_array['fio'] = "ФИО должно содержать только буквы, пробелы и дефисы";
    }

    if (empty($_POST['phone'])) {
        $errors_array['phone'] = "Заполните номер телефона.";
    }
    elseif (!preg_match('/^[\+\(\)\d\s-]+$/', $_POST['phone'])) {
        $errors_array['phone'] = "Номер телефона может содержать цифры, +, пробелы, скобки и дефисы.";
    }

    if (empty($_POST['email'])) {
        $errors_array['email'] = "Заполните почту.";
    }
    elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors_array['email'] = "Введите корректный email адрес.";
    }

    if (empty($_POST['brithDate'])) {
        $errors_array['brithDate'] = "Заполните Дату рождения.";
    }

    if (empty($_POST['gender'])) {
        $errors_array['gender'] = "Выберите пол.";
    }

    if (empty($_POST['lang_id'])) {
        $errors_array['lang_id'] = "Выберите язык программирования.";
    }

    if (empty($_POST['contract'])) {
        $errors_array['contract'] = "Необходимо согласие с условиями.";
    }

    // Если есть ошибки - сохраняем всё в cookies
    if (!empty($errors_array)) {
        // Сохраняем данные полей
        setcookie('saved_fio', $_POST['fio'], 0, '/');
        setcookie('saved_phone', $_POST['phone'], 0, '/');
        setcookie('saved_email', $_POST['email'], 0, '/');
        setcookie('saved_brithDate', $_POST['brithDate'], 0, '/');
        setcookie('saved_gender', $_POST['gender'], 0, '/');
        setcookie('saved_bio', $_POST['bio'], 0, '/');
        
        if (!empty($_POST['lang_id'])) {
            setcookie('saved_lang_id', json_encode($_POST['lang_id']), 0, '/');
        } else {
            setcookie('saved_lang_id', '', time() - 3600, '/');
        }
        
        // Сохраняем ошибки в cookies (как JSON)
        setcookie('errors_array', json_encode($errors_array), 0, '/');
        
        // Просто перезагружаем страницу без параметров
        header('Location: ./');
        exit();
    }
    
    // Если ошибок нет - сохраняем данные на год
    $one_year = time() + 31536000;
    setcookie('saved_fio', $_POST['fio'], $one_year, '/');
    setcookie('saved_phone', $_POST['phone'], $one_year, '/');
    setcookie('saved_email', $_POST['email'], $one_year, '/');
    setcookie('saved_brithDate', $_POST['brithDate'], $one_year, '/');
    setcookie('saved_gender', $_POST['gender'], $one_year, '/');
    setcookie('saved_bio', $_POST['bio'], $one_year, '/');
    
    if (!empty($_POST['lang_id'])) {
        setcookie('saved_lang_id', json_encode($_POST['lang_id']), $one_year, '/');
    }
    
    // Очищаем cookies с ошибками
    setcookie('errors_array', '', time() - 3600, '/');

    $user = 'u82468'; 
    $pass = '3747530';
    $db = new PDO('mysql:host=localhost;dbname=u82468', $user, $pass,
        [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    try {
        $stmt = $db->prepare("INSERT INTO users (fio, phone, email, brithDate, gender, bio, contract) 
                              VALUES (:fio, :phone, :email, :brithDate, :gender, :bio, :contract)");
        $stmt->execute([
            ':fio' => $_POST['fio'],
            ':phone' => $_POST['phone'],
            ':email' => $_POST['email'],
            ':brithDate' => $_POST['brithDate'],
            ':gender' => $_POST['gender'],  
            ':bio' => $_POST['bio'],
            ':contract' => isset($_POST['contract']) ? 1 : 0
        ]);
        
        $user_id = $db->lastInsertId();
        
        $stmt = $db->prepare("INSERT INTO user_languages (user_id, lang_id) VALUES (:user_id, :lang_id)");
        foreach ($_POST['lang_id'] as $lang_id) {
            $stmt->execute([
                ':user_id' => $user_id,
                ':lang_id' => $lang_id
            ]);
        }
    }
    catch(PDOException $e){
        print('Error : ' . $e->getMessage());
        exit();
    }

    header('Location: ?save=1');
    exit();
?>