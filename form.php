<html>
<head>
    <link href="style.css" rel="stylesheet">
</head>
<body>
<form method="POST">
    <label>ФИО: <input name="fio"/></label>
    <label>Телефон: <input name="phone"/></label>
    <label>Почта: <input name="email"/></label>
    <label>Дата рождения: <input name="brithDate" type="date"/></label>
    <label>Пол:
        <input type="radio" name="gender" value="male">Мужской</input>
        <input type="radio" name="gender" value="female">Женский</input>
    </label>
    <label>Любимый язык программирования: 
<select name ="lang_id[]" multiple size="5">
    <option value="1">Pascal</option>
    <option value="2">C</option>
    <option value="3">C++</option>
    <option value="4">JavaScript</option>
    <option value="5">PHP</option>
    <option value="6">Python</option>
    <option value="7">Java</option>
    <option value="8">Haskel</option>
    <option value="9">Clojure</option>
    <option value="10">Prolog</option>
    <option value="11">Scala</option>
    <option value="12">Go</option>    
</select></label>
<label>Биография:</label> 
<textarea name= "bio"></textarea>
<contr>
<input type="checkbox" name="contract">С контрактом ознакомлен</input></contr>
<button type="submit">Сохранить</button>
</form>

</body>
</html>