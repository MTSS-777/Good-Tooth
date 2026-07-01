<?php

error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
// timezone para São Paulo América
date_default_timezone_set('America/Sao_Paulo');

ob_start();

require  __DIR__ . "/vendor/autoload.php";

// os headers abaixo são necessários para permitir o acesso à API por clientes externos ao domínio
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header('Access-Control-Allow-Credentials: true'); // Permitir credenciais

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

use CoffeeCode\Router\Router;

$route = new Router(url("api"),":");

$route->namespace("Source\Controller");

$route->group("/appointments");
$route->get("/select", "Appointments:select"); //DEU
$route->post("/register", "Appointments:register"); //DEU
$route->put("/update/{id}","Appointments:update"); //
$route->put("/update-status/{id}","Appointments:updateStatus"); // deu
$route->post("/select/{id}", "Appointments:listById"); // deu
$route->delete("/delete/{id}", "Appointments:delete"); // deu
$route->post("/select/dentist/{id}", "Appointments:listByDentist"); // deu
$route->post("/select/patient/{id}", "Appointments:listByPatient"); // deu
$route->group(null);

$route->group("/faqs");
$route->get("/select", "Faqs:select"); //deu
$route->post("/register", "Faqs:register"); //deu
$route->put("/update/{id}","Faqs:update"); // deu
$route->post("/select/{id}", "Faqs:listById"); // deu
$route->delete("/delete/{id}", "Faqs:delete"); //deu
$route->put("/soft-delete/{id}", "Faqs:softDeleteById");
$route->group(null);

$route->group("/faqs-categories");
$route->get("/select", "FaqsCategories:select"); //deu
$route->post("/register", "FaqsCategories:register"); //deu
$route->put("/update/{id}","FaqsCategories:update"); // deu
$route->post("/select/{id}", "FaqsCategories:listById"); // deu
$route->delete("/delete/{id}", "FaqsCategories:delete"); // deu
$route->group(null);

$route->group("/payments");
$route->get("/select", "Payments:select"); //deu
$route->post("/register","Payments:register"); //deu
$route->put("/update/{id}","Payments:update"); // deu
$route->post("/select/{id}", "Payments:listById"); //deu
$route->delete("/delete/{id}", "Payments:delete"); // deu
$route->group(null);

$route->group("/procedures");
$route->get("/select", "Procedures:select"); //DEU
$route->post("/register","Procedures:register"); //deu
$route->put("/update/{id}","Procedures:update"); // deu
$route->post("/select/{id}", "Procedures:listById"); //deu
$route->delete("/delete/{id}", "Procedures:delete"); // deu
$route->put("/soft-delete/{id}", "Procedures:softDeleteById");
$route->group(null);

$route->group("/users");
$route->get("/select", "Users:select"); //DEU
$route->post("/register","Users:register"); // DEU
$route->post("/register-admin", "Users:registerAdmin"); //DEU
$route->post("/login-admin","Users:loginAdmin"); // DEU
$route->post("/login-dentist","Users:loginDentist"); //DEU
$route->post("/login-patient","Users:loginPatient"); //DEU
$route->put("/update/{id}","Users:update"); // DEU
$route->put("/update-dentist/{id}","Users:updateDentist"); // DEU
$route->put("/update-admin/{id}","Users:updateAdmin"); // DEU
$route->post("/select/{id}", "Users:listById"); // DEU
$route->put("/soft-delete/{id}", "Users:softDeleteById"); //DEU
$route->group(null);

$route->group("/users-types");
$route->get("/select", "UserTypes:select"); //deu
$route->post("/register","UserTypes:register"); //deu
$route->put("/update/{id}","UserTypes:update"); // deu
$route->post("/select/{id}", "UserTypes:listById"); //deu
$route->group(null);

$route->dispatch();

if ($route->error()) {
    header('Content-Type: application/json; charset=UTF-8');
    http_response_code(404);

    echo json_encode([
        "code" => 404,
        "type" => "error",
        "status" => "not_found",
        "message" => "O recurso solicitado não existe."
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

ob_end_flush();