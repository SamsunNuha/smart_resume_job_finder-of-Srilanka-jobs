<?php
// includes/template_config.php

$templates = [
    1 => [
        'name' => 'Classic Professional',
        'type' => 'Free',
        'file' => 'classic_professional.php',
        'description' => 'Simple black & white layout, ATS optimized.',
        'image' => 'template1.png'
    ],
    2 => [
        'name' => 'Modern Simple',
        'type' => 'Free',
        'file' => 'modern_simple.php',
        'description' => 'Clean blue header, two-column layout perfect for freshers.',
        'image' => 'template2.png'
    ],
    3 => [
        'name' => 'Executive Pro',
        'type' => 'Pro',
        'file' => 'executive_pro.php',
        'description' => 'Elegant serif typography with corporate spacing.',
        'image' => 'template3.png'
    ],
    4 => [
        'name' => 'Creative Designer',
        'type' => 'Pro',
        'file' => 'creative_designer.php',
        'description' => 'Portfolio-focused with colorful highlights.',
        'image' => 'template4.png'
    ],
    5 => [
        'name' => 'Tech Developer',
        'type' => 'Pro',
        'file' => 'tech_developer.php',
        'description' => 'Dark-themed, GitHub and skills focused.',
        'image' => 'template5.png'
    ],
    6 => [
        'name' => 'AI Smart Professional',
        'type' => 'Pro',
        'file' => 'ai_smart.php',
        'description' => 'Auto-aligned, achievement-highlighted modern layout.',
        'image' => 'template6.png'
    ]
];

function getTemplate($id) {
    global $templates;
    return $templates[$id] ?? $templates[1];
}
?>
