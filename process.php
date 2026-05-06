<?php
/**
 * Process Student Data & Map CBC Pathways
 * Logic: STEM, Social Sciences, or Arts & Sports
 */

require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Collect Input from Form
    $name = trim($_POST['student_name']);
    
    // Scores
    $sci     = (int)$_POST['science'];
    $math    = (int)$_POST['maths'];
    $agric   = (int)$_POST['agric'];
    $pretech = (int)$_POST['pre_tech'];
    $eng     = (int)$_POST['english'];
    $kisw    = (int)$_POST['kiswahili'];
    $soc     = (int)$_POST['social'];
    $re      = (int)$_POST['re'];
    $cas     = (int)$_POST['cas'];

    try {
        // 2. Generate Automated Admission Number: RJSS/numberJS/year
        // Get the current count to determine the next number
        $stmt = $pdo->query("SELECT COUNT(id) as total FROM students");
        $countRow = $stmt->fetch();
        $nextNumber = $countRow['total'] + 1;
        
        $year = date("Y");
        // Formats number to 3 digits (e.g., 001, 002)
        $formattedNumber = str_pad($nextNumber, 3, "0", STR_PAD_LEFT);
        $adm_no = "RJSS/{$formattedNumber}JS/{$year}";

        // 3. Pathway Mapping Logic
        // Calculate Cluster Averages
        $stem_avg = ($sci + $math + $agric + $pretech) / 4;
        $social_avg = ($eng + $kisw + $soc + $re) / 4;
        $arts_score = $cas;

        // Determine the highest performing area
        if ($stem_avg >= $social_avg && $stem_avg >= $arts_score) {
            $assigned_pathway = "STEM";
        } elseif ($social_avg >= $stem_avg && $social_avg >= $arts_score) {
            $assigned_pathway = "Social Sciences";
        } else {
            $assigned_pathway = "Arts & Sports Science";
        }

        // 4. Insert into Database
        $sql = "INSERT INTO students (
                    adm_no, student_name, science_score, maths_score, 
                    agric_score, pre_tech_score, english_score, 
                    kiswahili_score, social_score, re_score, cas_score, 
                    assigned_pathway
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $adm_no, $name, $sci, $math, $agric, $pretech, 
            $eng, $kisw, $soc, $re, $cas, $assigned_pathway
        ]);

        // 5. Success Redirect
        header("Location: index.php?status=success&adm=" . urlencode($adm_no));
        exit();

    } catch (PDOException $e) {
        // Log error and redirect with error message
        error_log($e->getMessage());
        header("Location: index.php?status=error");
        exit();
    }
} else {
    // If someone tries to access process.php directly
    header("Location: index.php");
    exit();
}
?>