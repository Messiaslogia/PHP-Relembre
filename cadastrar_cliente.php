<?php

function limpar_texto($str)
{
    return preg_replace("/[^0-9]/", "", $str);
}

if (count($_POST) > 0) {

    include('conexao.php');

    $erro = false;
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $nascimento = $_POST['nascimento'];

    if (empty($nome) || empty($email) || empty($telefone) || empty($nascimento)) {
        echo "Preencha todos os campos obrigatórios.";
        exit; // Encerra a execução do script se algum campo estiver vazio.
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Preencha um email válido.";
        exit; // Encerra a execução do script se o email não for válido.
    }

    if (!empty($nascimento)) {
        $pedacos = array_reverse(explode('/', $nascimento));
        if (count($pedacos) == 3) {
            $nascimento = implode('-', array_reverse($pedacos));
        } else {
            $erro = "A data de nascimento deve seguir o padrão dia/mes/ano.";
        }
    }

    if (!empty($telefone)) {
        $telefone = limpar_texto($telefone);
        if (strlen($telefone) != 11) {
            $erro = "O telefone deve ser preenchido no padrão";
        }
    }

    if ($erro) {
        echo "<p><b>ERRO: $erro</b></p>";
    } else {
        $sql_code = "INSERT INTO clientes (nome, email, telefone, nascimento, data) VALUES ('$nome', '$email', '$telefone', '$nascimento', NOW())";
        $deu_certo = $mysqli->query($sql_code) or die($mysqli->error);
        if ($deu_certo) {
            echo "<p><b>CLIENTE CADASTRADO COM SUCESSO</b></p>";
            unset($_POST);
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de Cadastro</title>
</head>

<body>
    <a href="clientes.php">Voltar para lista de Clientes</a>

    <form action="" method="POST">
        <div>
            <p>
                <label for="">Nome:</label>
                <input type="text" value="<?php if (isset($_POST['nome'])) echo $_POST['nome']; ?>" name="nome">
            </p>

            <p>
                <label for="">E-mail:</label>
                <input type="email" name="email" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>">
            </p>

            <p>
                <label for="">Telefone:</label>
                <input type="text" placeholder="(11) 646546546" name="telefone" value="<?php if (isset($_POST['telefone'])) echo $_POST['telefone']; ?>">
            </p>

            <p>
                <label for="">Data de Nascimento</label>
                <input type="text" name="nascimento" value="<?php if (isset($_POST['nascimento'])) echo $_POST['nascimento']; ?>">
            </p>

            <button type="submit">Enviar</button>
        </div>
    </form>

</body>

</html>