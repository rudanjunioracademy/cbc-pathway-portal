<?php
/**
 * Export Script for Rudan CBC System
 * Generates data.json for GitHub Pages hosting.
 */

require_once 'db_connect.php';

try {
    // 1. Fetch only necessary public data (Admission No and Pathway)
    // We exclude names and raw scores to maintain student privacy on GitHub
    $stmt = $pdo->query("SELECT adm_no, assigned_pathway FROM students ORDER BY created_at DESC");
    $students = $stmt->fetchAll();

    // 2. Convert the array to a JSON string
    // JSON_PRETTY_PRINT makes it readable, but you can remove it for a smaller file size
    $jsonData = json_encode($students, JSON_PRETTY_PRINT);

    // 3. Save the file to the current directory
    // This file must be in the folder you 'git push' to GitHub
    $fileName = 'data.json';
    
    if (file_put_contents($fileName, $jsonData)) {
        // Success message with instructions for Git Bash
        echo "<div style='font-family: sans-serif; padding: 20px; border: 1px solid #28a745; background: #d4edda; color: #155724;'>";
        echo "<h2>Data Exported Successfully!</h2>";
        echo "<p><strong>$fileName</strong> has been updated with " . count($students) . " records.</p>";
        echo "<hr>";
        echo "<p>Next steps in <strong>Git Bash</strong>:</p>";
        echo "<code>git add $fileName<br>git commit -m 'Update student pathways'<br>git push origin main</code>";
        echo "</div>";
    } else {
        throw new Exception("Unable to write to $fileName. Check folder permissions.");
    }

} catch (Exception $e) {
    echo "<div style='font-family: sans-serif; padding: 20px; border: 1px solid #dc3545; background: #f8d7da; color: #721c24;'>";
    echo "<h2>Export Failed</h2>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
    echo "</div>";
}
?>