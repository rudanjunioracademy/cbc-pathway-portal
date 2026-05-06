<?php
require_once 'db_connect.php';

// Fetch existing students to display in the table
$stmt = $pdo->query("SELECT * FROM students ORDER BY created_at DESC");
$students = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rudan Junior Secondary | CBC Pathway Mapper</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; margin: 0; padding: 20px; }
        .container { max-width: 1000px; margin: auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1, h2 { color: #2c3e50; }
        .grid-form { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; margin-bottom: 20px; }
        .form-group { display: flex; flex-direction: column; }
        label { font-weight: bold; margin-bottom: 5px; font-size: 0.9em; }
        input { padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        button { grid-column: span 3; padding: 15px; background: #27ae60; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 1em; }
        button:hover { background: #219150; }
        table { width: 100%; border-collapse: collapse; margin-top: 30px; }
        th, td { text-align: left; padding: 12px; border-bottom: 1px solid #eee; }
        th { background-color: #34495e; color: white; }
        .status-msg { padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .success { background: #d4edda; color: #155724; }
        .pathway-badge { padding: 5px 10px; border-radius: 12px; font-size: 0.8em; font-weight: bold; background: #e0e0e0; }
    </style>
</head>
<body>

<div class="container">
    <h1>Rudan Junior Secondary School</h1>
    <p>Grade 9 to Senior School Pathway Mapper</p>

    <?php if(isset($_GET['status']) && $_GET['status'] == 'success'): ?>
        <div class="status-msg success">
            Student added successfully! Admission Number: <strong><?php echo htmlspecialchars($_GET['adm']); ?></strong>
        </div>
    <?php endif; ?>

    <form action="process.php" method="POST">
        <h2>Enter Student Scores</h2>
        <div class="grid-form">
            <!-- Student Info -->
            <div class="form-group" style="grid-column: span 3;">
                <label>Full Name</label>
                <input type="text" name="student_name" placeholder="e.g. John Doe" required>
            </div>

            <!-- STEM Cluster -->
            <div class="form-group">
                <label>Science</label>
                <input type="number" name="science" min="0" max="100" required>
            </div>
            <div class="form-group">
                <label>Maths</label>
                <input type="number" name="maths" min="0" max="100" required>
            </div>
            <div class="form-group">
                <label>Agric</label>
                <input type="number" name="agric" min="0" max="100" required>
            </div>
            <div class="form-group">
                <label>Pre-Tech</label>
                <input type="number" name="pre_tech" min="0" max="100" required>
            </div>

            <!-- Social Science Cluster -->
            <div class="form-group">
                <label>English</label>
                <input type="number" name="english" min="0" max="100" required>
            </div>
            <div class="form-group">
                <label>Kiswahili</label>
                <input type="number" name="kiswahili" min="0" max="100" required>
            </div>
            <div class="form-group">
                <label>Social Studies</label>
                <input type="number" name="social" min="0" max="100" required>
            </div>
            <div class="form-group">
                <label>R.E (CRE/IRE)</label>
                <input type="number" name="re" min="0" max="100" required>
            </div>

            <!-- Arts/Sports -->
            <div class="form-group">
                <label>C.A.S</label>
                <input type="number" name="cas" min="0" max="100" required>
            </div>

            <button type="submit">Generate Admission Number & Map Pathway</button>
        </div>
    </form>

    <hr>

    <h2>Recent Mappings</h2>
    <a href="export.php" style="float: right; color: #2980b9; text-decoration: none; font-weight: bold;">[!] Export to GitHub Pages</a>
    <table>
        <thead>
            <tr>
                <th>ADM No</th>
                <th>Name</th>
                <th>Pathway</th>
                <th>Date Added</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($students as $row): ?>
            <tr>
                <td><strong><?php echo $row['adm_no']; ?></strong></td>
                <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                <td><span class="pathway-badge"><?php echo $row['assigned_pathway']; ?></span></td>
                <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>