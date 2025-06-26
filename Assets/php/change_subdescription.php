<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  // Accès direct ou mauvaise méthode, on bloque
  die('Accès interdit.');
}

// Récupérer les paramètres depuis l'URL (GET) ou le formulaire (POST)
$task_id = isset($_REQUEST['task_id']) ? intval($_REQUEST['task_id']) : null;
$subtask_id = isset($_REQUEST['subtask_id']) ? intval($_REQUEST['subtask_id']) : null;

// Charger la config MYSQL CRENTIALS !!!!
$config = require __DIR__ . '/.env.php';

$dsn = "mysql:host={$config['db_host']};dbname={$config['db_name']};charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $config['db_user'], $config['db_pass'], $options);
    // echo "ok db <br>";

    $task_id = isset($_REQUEST['task_id']) ? intval($_REQUEST['task_id']) : null;
    $subtask_id = isset($_REQUEST['subtask_id']) ? intval($_REQUEST['subtask_id']) : null;
    $subtask_txt = isset($_REQUEST['text']) ? $_REQUEST['text'] : null;

    // Pour vérifier :
    if ($task_id && $subtask_id) {
        // Les deux paramètres sont présents
        // Tu peux continuer le traitement ici
        // Par exemple :
        echo "<br>Task ID : $task_id, <br>Subtask ID : $subtask_id";
        echo "<br>Text : $subtask_txt";
    } else {
        // Paramètres manquants
        echo "Paramètres manquants<br>";
    }

    // $pdo est ta connexion PDO
    $stmt = $pdo->prepare("SELECT id, task_id, title, due_description FROM subtasks WHERE id = ?");
    $stmt->execute([$subtask_id]);
    $row = $stmt->fetch();

    if ($row) {
        echo "Sous-tâche #{$row['id']}<br>";
        echo "Tâche parente : {$row['task_id']}<br>";
        echo "Titre : {$row['title']}<br>";
        echo "Description : {$row['due_description']}<br>";
    } else {
        echo "<br><strong style='color:red'>Aucune sous-tâche trouvée avec cet id.</strong><br>";
    }

    if ($subtask_id && $subtask_txt !== null) {
        $stmt = $pdo->prepare("UPDATE subtasks SET due_description = ? WHERE id = ?");
        $success = $stmt->execute([$subtask_txt, $subtask_id]);
        if ($success) {
            echo "<br><strong style='color:green'>Description mise à jour avec succès !</strong>";
        } else {
            echo "<br><strong style='color:red'>Erreur lors de la mise à jour</strong><br>";
        }
    } else {
        echo "<br><strong style='color:red'>Paramètres manquants (2).</strong><br>";
    }

} catch (\PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
    exit;
}

?>
