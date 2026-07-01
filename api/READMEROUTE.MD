# 🦷 GOOD TOOTH

 Lista de rotas

---

## 📅 Appointments

Rotas relacionadas ao agendamento de consultas.

### `SELECT`
$route->get("/select", "Appointments:select")
Lista todos os agendamentos cadastrados. (Precisa estar autenticado como admin (1))

$this->call(200, "success", "Lista de consultas", "success")->back($appointment->selectAll());

### `SELECT BY ID`
$route->post("/select/{id}", "Appointments:listById")
Retorna os detalhes de um agendamento específico a partir do id na rota. (Precisa estar autenticado como admin (1))

### `REGISTER`
$route->post("/register", "Appointments:register")
Cria um novo pedido de agendamento com status 'pending' (aguardando atualização do dentista). (Precisa estar autenticado como paciente (3), pois somente pacientes podem requerir consultas)

$response = [
            "id" => $appointment->getId(),
            "paciente" => $appointment->getPatientId(),
            "dentista" => $appointment->getDentistId()
        ];
        $this->call(400, "success", "Consulta marcada com sucesso! Aguarde a confirmação do dentista." , "success")->back($response);

### `UPDATE`
$route->put("/update/{id}","Appointments:update")
Atualiza um agendamento existente a partir do id, podendo atualizar dentista responsável pela consulta (dentistId), procedimento da consulta (procedureId), data ou hora. (Precisa estar autenticado como paciente (3), pois somente pacientes podem requerir atualização das próprias consultas) (Ao atualizar, o status da consulta é setado para 'pending' novamente e deve passar por outra autorização do dentista)

$this->call(201,"success","Consulta atualizada com sucesso","success")->back($response);

### `UPDATE STATUS`
$route->put("/update-status/{id}","Appointments:updateStatus")
Atualiza especificamente o status de uma consulta. Essa rota é exclusiva dos dentistas (2) para mudar o status das consultas para 'completed', 'cancelled' ou 'confirmed'.

$response = [
            "id" => $appointment->getId(),
            "date" => $appointment->getAppointmentDate(),
            "time" => $appointment->getAppointmentTime(),
            "status" => $appointment->getStatus()
        ];
        $this->call(201,"success","Consulta atualizada","success")->back($response);
    }

### `DELETE`
$route->delete("/delete/{id}", "Appointments:delete")
Remove um agendamento usando o delete da Model. (Precisa estar autenticado como admin(1) e serve para casos específicos de delete, pois as consultas ja tem status proprio (pending, completed, cancelled, confirmed))

### `SELECT by DENTIST`
$route->post("/select/dentist/{id}", "Appointments:listByDentist")
Lista todos os agendamentos de um dentista específico a partir do dentistId das appointments.

$this->call(200, "success", "Consultas do dentista encontradas.", "success")->back($results);

### `SELECT by PATIENT`
$route->post("/select/patient/{id}", "Appointments:listByPatient")
Lista todos os agendamentos de um paciente específico a partir do patientId das appointments.

$this->call(200, "success", "Consultas do paciente encontradas.", "success")->back($results);

---

## ❓ Faqs

Rotas relacionadas as perguntas frequentes.

### `SELECT`
$route->get("/select", "Faqs:select")
Lista todos as Faqs.

### `SELECT BY ID`
$route->post("/select/{id}", "Faqs:listById")
Retorna os detalhes de uma faq específica a partir do id na rota. (Precisa estar autenticado como admin (1))

### `REGISTER`
$route->post("/register", "Faqs:register")
Cria uma nova Faq (Precisa estar autenticado como admin (1)

### `UPDATE`
$route->put("/update/{id}","Faqs:update")
Atualiza uma faq existente a partir do id, podendo atualizar a categoria, a pergunta ou a resposta da faq (Precisa estar autenticado como admin (1))

### `DELETE`
$route->put("/soft-delete/{id}", "Faqs:softDeleteById")
Remove um agendamento usando o soft delete da Model. (Precisa estar autenticado como admin(1))

---

## 📚 Faqs Categories
Rotas relacionadas as categorias das faqs.

### `SELECT`
$route->get("/select", "FaqsCategories:select")
Lista todos as categorias de Faqs. (Precisa estar autenticado como admin(1))

### `SELECT BY ID`
$route->post("/select/{id}", "FaqsCategories:listById")
Retorna os detalhes de uma categoria específica a partir do id na rota. (Precisa estar autenticado como admin (1))

### `REGISTER`
$route->post("/register", "FaqsCategories:register")
Cria uma nova categoria (Precisa estar autenticado como admin (1)

### `UPDATE`
$route->put("/update/{id}","FaqsCategories:update")
Atualiza uma categoria existente a partir do id, podendo atualizar o nome (Precisa estar autenticado como admin (1))

### `DELETE`
$route->delete("/delete/{id}", "FaqsCategories:delete")
Remove um agendamento usando o delete da Model. (Precisa estar autenticado como admin(1))

---

## 💳 Payments
Rotas relacionadas aos pagamentos.

### `SELECT`
$route->get("/select", "Payments:select")
Lista todos os pagamentos. (Precisa estar autenticado como admin(1))

### `SELECT BY ID`
$route->post("/select/{id}", "Payments:listById")
Retorna os detalhes de um pagamento específico a partir do id na rota. (Precisa estar autenticado como admin (1))

### `REGISTER`
$route->post("/register","Payments:register")
Cria um novo pagamento assim que a consulta relacionada ter o status setado como 'completed'.

### `UPDATE`
$route->put("/update/{id}","Payments:update")
Atualiza um pagamento existente a partir do id (Precisa estar autenticado como admin (1))

---

## 🦷 Procedures
Rotas relacionadas aos procedimentos.

### `SELECT`
$route->get("/select", "Procedures:select")
Lista todos os procedimentos.

### `SELECT BY ID`
$route->post("/select/{id}", "Procedures:listById")
Retorna os detalhes de um procedimento específico a partir do id na rota. (Precisa estar autenticado como admin (1))

### `REGISTER`
$route->post("/register","Procedures:register")
Cria um novo procedimento (Precisa estar autenticado como admin (1)

### `UPDATE`
$route->put("/update/{id}","Procedures:update")
Atualiza um procedimento existente a partir do id (Precisa estar autenticado como admin (1))

### `DELETE`
$route->put("/soft-delete/{id}", "Procedures:softDeleteById")
Remove um agendamento usando o soft delete da Model. (Precisa estar autenticado como admin(1))

---

## 👤 Users
Rotas relacionadas aos usuários.

### `SELECT`
$route->get("/select", "Users:select")
Lista todos os usuários (precisa estar autenticado como admin(1)).

### `SELECT BY ID`
$route->post("/select/{id}", "Users:listById")
Retorna os detalhes de um usuário específico a partir do id na rota. (Precisa estar autenticado como admin (1))

### `REGISTER`
$route->post("/register","Users:register")
Cria um novo usuário (paciente ou dentista) passando o typeId (2 ou 3), name, email e password.

### `REGISTER ADMIN`
$route->post("/register-admin","Users:registerAdmin")
Cria um novo usuário admin passando o typeId (1), name, email e password.

### `LOGIN ADMIN`
$route->post("/login-admin","Users:loginAdmin")
Faz o login do admin a partir do email e senha.

### `LOGIN PATIENT`
$route->post("/login-patient","Users:loginPatient")
Faz o login do paciente a partir do email e senha.

$response = [
            "id" => $user->getId(),
            "name" => $user->getName(),
            "token" => $user->getToken()
        ];
        $this->call(
            200,
            "success",
            "Usuário logado com sucesso",
            "success")->back($response);

### `LOGIN DENTIST`
$route->post("/login-dentist","Users:loginDentist")
Faz o login do dentista a partir do email e senha.

### `UPDATE`
$route->put("/update/{id}","Users:update")
Atualiza um usuário paciente (3) existente a partir do id (Precisa estar autenticado como paciente (3) pois somente o paciente altera sua senha)

### `UPDATE DENTIST`
$route->put("/update/dentist/{id}","Users:updateDentist")
Atualiza um usuário dentista (2) existente a partir do id (Precisa estar autenticado como admin (1))

### `UPDATE ADMIN`
$route->put("/update/admin/{id}","Users:updateAdmin")
Atualiza um usuário admin (1) existente a partir do id (Precisa estar autenticado como admin (1).

### `DELETE`
$route->put("/soft-delete/{id}", "Users:softDeleteById")
Remove um usuário usando o soft delete da Model.

---