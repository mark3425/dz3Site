    <?php
    header('Content-Type: text/html; charset=UTF-8');


    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!empty($_GET['save'])) {
        print('Спасибо, результаты сохранены.');
    }
    include('form.php');
    exit();
    }

    $errors = FALSE;
    if (empty($_POST['fio'])) {
    print('Заполните имя.<br/>');
    $errors = TRUE;
    }
    elseif (strlen($_POST['fio'])>150) {
    print('Слишком много символов.<br/>');
    $errors = TRUE;
    }

    if (empty($_POST['phone'])) {
    print('Заполните номер телефона.<br/>');
    $errors = TRUE;
    }
    elseif (!preg_match('/^\+?\d+$/', $_POST['phone'])) {
    print('Номер телефона должен содержать только цифры и может начинаться с +.<br/>');
    $errors = TRUE;
}
    if (empty($_POST['email'])) {
    print('Заполните почту.<br/>');
    $errors = TRUE;
    }
    elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    print('Введите корректный email адрес.<br/>');
    $errors = TRUE;
}

    if (empty($_POST['brithDate'])) {
    print('Заполните Дату рождения.<br/>');
    $errors = TRUE;
    }

    if (empty($_POST['gender'])) {
    print('Выберите пол.<br/>');
    $errors = TRUE;
    }
    
    if (empty($_POST['lang_id'])) {
    print('Выберите язык программирования.<br/>');
    $errors = TRUE;
    }



    if ($errors) {
    exit();
    }

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
    
    // Получаем ID созданного пользователя
    $user_id = $db->lastInsertId();
    
    // 2. Сохраняем выбранные языки
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
