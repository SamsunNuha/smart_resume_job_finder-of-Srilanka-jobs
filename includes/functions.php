<?php
// includes/functions.php

/**
 * Calculate ATS Score based on resume completion
 * @param array|null $resume
 * @return int
 */
function getResumeScore($resume) {
    if (!$resume) return 0;
    $score = 0;
    if (!empty($resume['full_name'])) $score += 10;
    if (!empty($resume['summary'])) $score += 15;
    if (!empty($resume['email']) && !empty($resume['phone'])) $score += 10;
    
    $edu = json_decode($resume['education'], true);
    if (!empty($edu)) $score += 20;
    
    $exp = json_decode($resume['experience'], true);
    if (!empty($exp)) $score += 15;
    
    $skills = array_filter(explode(',', $resume['skills'] ?? ''));
    if (count($skills) >= 5) $score += 20;
    else if (count($skills) > 0) $score += 10;
    
    $proj = json_decode($resume['projects'], true);
    if (!empty($proj)) $score += 10;
    
    return $score;
}
