<?php
include  '../db.php';


$query = "SELECT question_text, answer_text FROM user_questions";
$statement = $db->prepare($query);
$statement->execute();
$faqs = $statement->fetchAll(PDO::FETCH_ASSOC);

include 'ask_question_form.php'; 


foreach ($faqs as $faq) {
    echo "<div class='faq-item'>";
    echo "<h3>" . htmlspecialchars($faq['question_text']) . "</h3>";
    echo "<p>" . nl2br(htmlspecialchars($faq['answer_text'])) . "</p>";
    echo "</div>";
}
?>
