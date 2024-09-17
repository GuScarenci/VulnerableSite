<?php
session_start();

// Conexão com o banco de dados
$conn = new mysqli('localhost', 'usuario', '1234', 'site_vulneravel');

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Função de login (Vulnerável a SQL Injection)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = $_POST['username'];
    $senha = $_POST['senha'];
    
    // Consulta SQL vulnerável a SQL Injection
    $sql = "SELECT * FROM usuarios WHERE username = '$username' AND senha = '$senha'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Login bem-sucedido, armazena o usuário na sessão
        $_SESSION['logado'] = true;
        echo "<p class='success'>Login realizado com sucesso!</p>";
    } else {
        echo "<p class='error'>Login inválido!</p>";
    }
}

// Função de logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// Cria a tabela de mensagens se não existir
$sql = "CREATE TABLE IF NOT EXISTS mensagens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255),
    mensagem TEXT
)";
$conn->query($sql);

// Inserção de dados (Vulnerável a SQL Injection)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['mensagem'])) {
    if (isset($_SESSION['logado']) && $_SESSION['logado'] == true) {
        $nome = addslashes($_POST['nome']);
        $mensagem = $_POST['mensagem'];
        
        // Usar multi_query para aceitar múltiplas consultas
        $sql = "INSERT INTO mensagens (nome, mensagem) VALUES ('$nome', '$mensagem');";
        // Se múltiplas consultas forem injetadas, elas serão executadas
        if ($conn->multi_query($sql)) {
            do {
                // Limpar resultados de possíveis múltiplas consultas
                if ($result = $conn->store_result()) {
                    $result->free();
                }
            } while ($conn->more_results() && $conn->next_result());
        } else {
		echo "Erro: " . $conn->error;
	}
    } else {
        echo "<p class='error'>Você precisa estar logado para enviar uma mensagem.</p>";
    }
}

// Exibição de dados (Vulnerável a XSS)
$sql = "SELECT * FROM mensagens";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livro de Visitas Vulnerável</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
            text-align: center;
        }
        h1, h2 {
            color: #333;
        }
        form {
            margin-bottom: 20px;
        }
        input, textarea {
            padding: 10px;
            margin: 5px;
            width: 90%;
            max-width: 400px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        .btn {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #218838;
        }
        .message {
            background-color: white;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .message h3 {
            margin: 0 0 5px 0;
        }
        .message p {
            color: #666;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
        .logout {
            color: red;
            text-decoration: none;
            padding: 10px;
            margin-bottom: 20px;
            display: inline-block;
        }
        .logout:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<h1>Livro de Visitas Vulnerável</h1>

<?php
if (isset($_SESSION['logado']) && $_SESSION['logado'] == true) {
    echo "<a class='logout' href='?logout=true'>Logout</a>";
    echo "<h2>Postar Mensagem</h2>";
    echo "<form method='POST'>
            <input type='text' name='nome' placeholder='Seu nome'><br>
            <textarea name='mensagem' placeholder='Sua mensagem'></textarea><br>
            <input class='btn' type='submit' name='enviar' value='Enviar'>
          </form>";
} else {
    echo "<h2>Login</h2>";
    echo "<form method='POST'>
            <input type='text' name='username' placeholder='Usuário'><br>
            <input type='password' name='senha' placeholder='Senha'><br>
            <input class='btn' type='submit' name='login' value='Entrar'>
          </form>";
}

if ($result->num_rows > 0) {
    echo "<h2>Mensagens</h2>";
    while($row = $result->fetch_assoc()) {
        echo "<div class='message'>";
        echo "<h3>".$row['nome']."</h3>";
	echo "<p>".$row['mensagem']."</p>";
echo "</div>";
    }
} else {
    echo "<p>Nenhuma mensagem ainda.</p>";
}

$conn->close();
?>

</body>
</html>
