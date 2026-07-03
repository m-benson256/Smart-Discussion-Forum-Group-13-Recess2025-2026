<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=smart_discussion_forum', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== migrations ===\n";
    $stmt = $pdo->query('SELECT migration, batch FROM migrations ORDER BY batch, migration');
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo $row['migration'] . ' (batch ' . $row['batch'] . ")\n";
    }
    
    echo "\n=== topics columns ===\n";
    $stmt = $pdo->query('DESCRIBE topics');
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo $row['Field'] . ' (' . $row['Type'] . ')  null=' . $row['Null'] . '  key=' . $row['Key'] . "\n";
    }
    
    echo "\n=== groups columns ===\n";
    $stmt = $pdo->query('DESCRIBE `groups`');
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo $row['Field'] . ' (' . $row['Type'] . ')  null=' . $row['Null'] . '  key=' . $row['Key'] . "\n";
    }
    
    echo "\n=== members columns ===\n";
    $stmt = $pdo->query('DESCRIBE members');
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo $row['Field'] . ' (' . $row['Type'] . ')  null=' . $row['Null'] . '  key=' . $row['Key'] . "\n";
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
