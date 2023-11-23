<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle de Clientes</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 20px;
            background-color: #f6f6f64;
        }

        form {
            max-width: 400px;
            margin: 20px 0;
            padding: 20px;
            border-radius: 8px;
            background-color: #bbb;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        form label {
            display: block;
            margin-bottom: 8px;
            color: #111;
        }

        form input, form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        form input[type="submit"] {
            background-color: blue;
            color: black;
            cursor: pointer;
        }

        h2 {
            margin-top: 20px;
            color: #111;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 10px;
            padding: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        p {
            color: #333;
        }

        div.success {
            color: #verde;
            font-weight: bold;
        }

        div.error {
            color: #f44336;
            font-weight: bold;
        }
    </style>
</head>
<body>

<?php
$servername = "localhost:3306";
$username = "root";
$password = "";
$database = "gerenciamentotarefas";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Verifica se o formulário foi enviado.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se os campos estão definidos antes de acessá-los
    $nome = isset($_POST["nome"]) ? $_POST["nome"] : "";
    $email = isset($_POST["email"]) ? $_POST["email"] : "";
    $telefone = isset($_POST["telefone"]) ? $_POST["telefone"] : "";

    // Verifica se todos os campos estão preenchidos
    if ($nome && $email && $telefone) {
        // Usa prepared statements para evitar injeção de SQL
        $sql = "INSERT INTO clientes (nome, email, telefone) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // Vincula os parâmetros
        $stmt->bind_param("sss", $nome, $email, $telefone);

        // Executa a query
        if ($stmt->execute()) {
            echo "<div class='success'>Cliente adicionado com sucesso.</div>";
        } else {
            echo "<div class='error'>Erro ao adicionar cliente: " . $conn->error . "</div>";
        }

        // Fecha a declaração preparada
        $stmt->close();
    } else {
        echo "<div class='error'>Todos os campos são obrigatórios.</div>";
    }
}

// Consulta e exibe a lista de clientes.
$sql = "SELECT nome, email, telefone FROM clientes";
$result = $conn->query($sql);
?>

<!-- Formulário para adicionar clientes -->
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="nome">Nome do Cliente:</label>
    <input type="text" name="nome" id="nome" required><br>

    <label for="email">E-mail do Cliente:</label>
    <input type="email" name="email" id="email" required><br>

    <label for="telefone">Telefone do Cliente:</label>
    <input type="tel" name="telefone" id="telefone" required><br>

    <input type="submit" value="Adicionar Cliente">
</form>

<!-- Lista de clientes -->
<?php
if ($result->num_rows > 0) {
    echo "<h2>Lista de Clientes</h2>";
    echo "<ul>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<li>Nome: " . htmlspecialchars($row["nome"]) . ", E-mail: " . htmlspecialchars($row["email"]) . ", Telefone: " . htmlspecialchars($row["telefone"]) . "</li>";
    }
    
    echo "</ul>";
} else {
    echo "<p>Nenhum cliente adicionado ainda.</p>";
}

$conn->close();
?>

</body>
</html>