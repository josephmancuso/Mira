<?php

function file_get_php_classes($filepath)
{
    $php_code = file_get_contents($filepath);
    $classes = get_php_classes($php_code);
    return $classes;
}

function get_php_classes($php_code)
{
    $classes = array();
    $tokens = token_get_all($php_code);
    $count = count($tokens);
    for ($i = 2; $i < $count; $i++) {
        if ($tokens[$i - 2][0] == T_CLASS
            && $tokens[$i - 1][0] == T_WHITESPACE
            && $tokens[$i][0] == T_STRING) {
            $class_name = $tokens[$i][1];
            $classes[] = $class_name;
        }
    }
    return $classes;
}

Route::get("admin/{model}/view/$", function ($model) {
    $placeholder = $model;
    $model = new $placeholder();
    Render::view("admin.model-view", [
        "models" => $model->all(),
        "model_name" => $placeholder,
    ]);
});

Route::get("admin/{model}/edit/{id}", function ($model, $id) {
    $placeholder = $model;
    $model = new $placeholder();
    
    $model->filter("id = '$id' ");
    Render::view("admin.model-edit", [
        "models" => $model->filter("id = '$id' "),
        "model_name" => $placeholder,
        "structure" => $model->structure,
    ]);
});

Route::post("admin/{model}/edit/{id}", function ($model, $id) {
    $placeholder = $model;
    $model = new $placeholder();

    $model->updateFromPost($_POST, "id = '$id'");
    Render::view("admin.model-edit", [
        "models" => $model->filter("id = '$id' "),
        "model_name" => $placeholder,
        "structure" => $model->structure,
        "updated" => true,
    ]);
});

Route::get("admin/$", function () {
    if (!$_SESSION['logged']) {
         Render::redirect("/admin/login/");
    }
    $config = include '../../config/config.php';
    foreach ($config['models'] as $app) {
        $site_classes[] = $app;
        $site_classes[] = file_get_php_classes("../app/$app/models/models.php");
    }


    echo "<pre>";
    print_r($site_classes);
    echo "</pre>";

    //$site_classes = file_get_php_classes("../models/models.php");

    Render::view("admin.index", [
        "site_classes" => $site_classes,
    ]);
});

Route::get("admin/login/$", function () {
    Render::view("admin.login");
});

Route::post("admin/login/$", function () {
    $handler = new PDO(
        'mysql:host=localhost;dbname=mysql',
        $_POST['username'],
        $_POST['password']
    );

    $username = $_POST['username'];
    $password = $_POST['password'];
    $query = $handler->query("SELECT * FROM mysql.user WHERE User = '$username' AND Password = PASSWORD('$password')");

    $arr = $query->fetchAll();
    
    if ($arr[0]['Super_priv'] == "Y") {
        $_SESSION['logged'] = true;
        Render::redirect("/admin/");
    }
});
