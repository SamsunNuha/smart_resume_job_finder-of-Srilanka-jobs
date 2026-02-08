<?php
// user/templates/classic_professional.php
// Template 1: Classic Professional
// Simple Black & White, Left Aligned, Minimal Design
?>
<div id="resume-a4" class="a4-page">
    <div style="padding: 30px 60px; font-family: 'Merriweather', serif; color: #111;">
        
        <!-- Header -->
        <div style="border-bottom: 2px solid #000; padding-bottom: 15px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: flex-start; gap: 20px;">
            <div style="flex: 1; min-width: 0;">
                <h1 style="font-size: 28pt; font-weight: 700; margin: 0; text-transform: uppercase; letter-spacing: 1px;">
                    <?php echo htmlspecialchars($resume['full_name']); ?>
                </h1>
                <?php if(!empty($extra['job_title'])): ?>
                    <div style="font-size: 14pt; color: #333; font-weight: 600; margin: 5px 0; text-transform: uppercase; letter-spacing: 2px;">
                        <?php echo htmlspecialchars($extra['job_title']); ?>
                    </div>
                <?php endif; ?>
                <?php if(!empty($extra['highest_degree'])): ?>
                    <div style="font-size: 11pt; color: #555; margin-bottom: 10px;">
                        <?php echo htmlspecialchars($extra['highest_degree']); ?>
                    </div>
                <?php endif; ?>
                
                <?php
                // Get initials for placeholder
                $initials = '';
                if (!empty($resume['full_name'])) {
                    $words = explode(' ', $resume['full_name']);
                    foreach ($words as $w) { $initials .= strtoupper(substr($w, 0, 1)); }
                    $initials = substr($initials, 0, 2); // Limit to 2 chars
                }
                ?>
                <div style="font-size: 10pt; color: #555; margin-bottom: 5px;"><?php echo htmlspecialchars($resume['address']); ?></div>
                <div style="font-size: 8pt; color: #333; display: flex; flex-wrap: wrap; gap: 8px; align-items: center; margin-top: 5px;">
                    <span style="display: flex; align-items: center; gap: 4px;"><i class="fa-solid fa-phone" style="font-size: 8pt; color: #666;"></i> <?php echo htmlspecialchars($resume['phone']); ?></span>
                    <span style="color: #ccc;">•</span>
                    <a href="mailto:<?php echo htmlspecialchars($resume['email']); ?>" style="color: #1d4ed8; text-decoration: none; display: flex; align-items: center; gap: 4px;"><i class="fa-solid fa-envelope" style="font-size: 8pt; color: #1d4ed8;"></i> <?php echo htmlspecialchars($resume['email']); ?></a>
                    
                    <?php if(!empty($extra['github'])): ?>
                        <span style="color: #ccc;">•</span>
                        <a href="https://github.com/<?php echo htmlspecialchars($extra['github']); ?>" target="_blank" style="color: #1d4ed8; text-decoration: none; display: flex; align-items: center; gap: 4px;"><i class="fa-brands fa-github" style="font-size: 9pt; color: #000;"></i> github.com/<?php echo htmlspecialchars($extra['github']); ?></a>
                    <?php endif; ?>

                    <?php if(!empty($extra['linkedin'])): ?>
                        <span style="color: #ccc;">•</span>
                        <a href="https://linkedin.com/in/<?php echo htmlspecialchars($extra['linkedin']); ?>" target="_blank" style="color: #1d4ed8; text-decoration: none; display: flex; align-items: center; gap: 4px;"><i class="fa-brands fa-linkedin" style="font-size: 9pt; color: #0077B5;"></i> linkedin.com/in/<?php echo htmlspecialchars($extra['linkedin']); ?></a>
                    <?php endif; ?>

                    <?php if(!empty($resume['website'])): ?>
                        <span style="color: #ccc;">•</span>
                        <a href="<?php echo htmlspecialchars($resume['website']); ?>" target="_blank" style="color: #1d4ed8; text-decoration: none; display: flex; align-items: center; gap: 4px;"><i class="fa-solid fa-globe" style="font-size: 8pt; color: #1d4ed8;"></i> Portfolio</a>
                    <?php endif; ?>
                </div>
            </div>
            <div style="flex-shrink: 0; width: 120px;">
                <?php if (!empty($resume['photo'])): ?>
                    <img src="<?php echo (strpos($resume['photo'], 'http') === 0) ? $resume['photo'] : '../uploads/resumes/' . $resume['photo']; ?>" 
                         style="width: 120px; height: 120px; border: 3px solid #000; padding: 4px; object-fit: cover; border-radius: 4px; display: block;">
                <?php else: ?>
                    <div style="width: 120px; height: 120px; background: #2563eb; color: white; display: flex; align-items: center; justify-content: center; font-size: 36pt; font-weight: 700; border: 3px solid #000; border-radius: 4px;">
                        <?php echo htmlspecialchars($initials); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Summary -->
        <div style="margin-bottom: 25px;">
            <h3 style="font-size: 12pt; font-weight: 700; text-transform: uppercase; margin: 0 0 10px 0; border-bottom: 1px solid #ccc; padding-bottom: 5px;">Professional Summary</h3>
            <p style="font-size: 10.5pt; line-height: 1.6; margin: 0; text-align: justify;">
                <?php echo nl2br(htmlspecialchars($resume['summary'])); ?>
            </p>
        </div>

        <!-- Experience -->
        <div style="margin-bottom: 25px;">
            <h3 style="font-size: 12pt; font-weight: 700; text-transform: uppercase; margin: 0 0 15px 0; border-bottom: 1px solid #ccc; padding-bottom: 5px;">Work Experience</h3>
            
            <?php foreach ($exp as $x): ?>
            <div style="margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 5px;">
                        <div style="font-weight: 700; color: #1e3a8a; font-size: 11.5pt;"><?php echo htmlspecialchars($x['role']); ?></div>
                        <div style="color: #64748b; font-size: 9.5pt; font-weight: 600;"><?php echo htmlspecialchars($x['company']); ?> | <?php echo htmlspecialchars($x['duration'] ?? 'Present'); ?></div>
                </div>
                <div style="font-size: 10.5pt; line-height: 1.5;">
                    <?php echo nl2br(htmlspecialchars($x['desc'] ?? '')); ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Education -->
        <div style="margin-bottom: 25px;">
            <h3 style="font-size: 12pt; font-weight: 700; text-transform: uppercase; margin: 0 0 15px 0; border-bottom: 1px solid #ccc; padding-bottom: 5px;">Education</h3>
            <?php foreach ($edu as $e): ?>
            <div style="margin-bottom: 10px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 2px;">
                    <div style="font-weight: 700; font-size: 11pt;"><?php echo htmlspecialchars($e['school']); ?></div>
                    <div style="font-size: 10pt;"><?php echo htmlspecialchars($e['year'] ?? ''); ?></div>
                </div>
                <div style="font-size: 10.5pt; font-weight: 600; margin-bottom: 2px;"><?php echo htmlspecialchars($e['degree']); ?></div>
                <?php if(!empty($e['gpa'])): ?>
                    <div style="font-size: 10pt; color: #111; margin-top: 2px;"><strong>Current GPA:</strong> <?php echo htmlspecialchars($e['gpa']); ?></div>
                <?php endif; ?>
                <?php if(!empty($e['subjects'])): ?>
                    <div style="font-size: 9.5pt; color: #555; margin-top: 2px; line-height: 1.3;"><strong>Key Subjects:</strong> <?php echo htmlspecialchars($e['subjects']); ?></div>
                <?php endif; ?>
                <?php if(!empty($e['desc'])): ?>
                    <div style="font-size: 10pt; color: #666; margin-top: 3px;"><?php echo nl2br(htmlspecialchars($e['desc'])); ?></div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Skills -->
        <div style="margin-bottom: 25px;">
            <h3 style="font-size: 12pt; font-weight: 700; text-transform: uppercase; margin: 0 0 10px 0; border-bottom: 1px solid #ccc; padding-bottom: 5px;">Skills</h3>
            <div style="font-size: 10.5pt; line-height: 1.6;">
                <?php 
                $skills_arr = explode(',', $resume['skills']);
                $skills_arr = array_map('trim', $skills_arr);
                echo implode(' • ', $skills_arr);
                ?>
            </div>
        </div>

        <?php if(!empty($proj)): ?>
        <!-- Projects -->
        <div style="margin-bottom: 25px;">
            <h3 style="font-size: 12pt; font-weight: 700; text-transform: uppercase; margin: 0 0 10px 0; border-bottom: 1px solid #ccc; padding-bottom: 5px;">Notable Projects</h3>
            <?php foreach($proj as $p): ?>
            <div style="margin-bottom: 10px;">
                <div style="font-weight: 700; font-size: 11pt;"><?php echo htmlspecialchars($p['name']); ?></div>
                <div style="font-size: 10.5pt; line-height: 1.4; color: #444;"><?php echo htmlspecialchars($p['description']); ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if(!empty($cert)): ?>
        <!-- Certifications -->
        <div style="margin-bottom: 25px;">
            <h3 style="font-size: 12pt; font-weight: 700; text-transform: uppercase; margin: 0 0 10px 0; border-bottom: 1px solid #ccc; padding-bottom: 5px;">Certifications</h3>
            <?php foreach($cert as $c): ?>
            <div style="margin-bottom: 8px; font-size: 10.5pt;">
                <strong><?php echo htmlspecialchars($c['name']); ?></strong> 
                <?php if(!empty($c['year'])): ?>
                    <span style="color: #333;"> (<?php echo htmlspecialchars($c['year']); ?>)</span>
                <?php endif; ?>
                <?php if(!empty($c['issuer'])): ?>
                    <span style="color: #666;"> — <?php echo htmlspecialchars($c['issuer']); ?></span>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if(!empty($extra['achievements'])): ?>
        <!-- Achievements -->
        <div style="margin-bottom: 25px;">
            <h3 style="font-size: 12pt; font-weight: 700; text-transform: uppercase; margin: 0 0 10px 0; border-bottom: 1px solid #ccc; padding-bottom: 5px;">Key Achievements</h3>
            <ul style="margin: 0; padding-left: 20px; font-size: 10.5pt; line-height: 1.5;">
                <?php foreach($extra['achievements'] as $a): ?>
                    <li><?php echo htmlspecialchars($a); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <?php if(!empty($extra['languages'])): ?>
        <!-- Languages -->
        <div style="margin-bottom: 25px;">
            <h3 style="font-size: 12pt; font-weight: 700; text-transform: uppercase; margin: 0 0 10px 0; border-bottom: 1px solid #ccc; padding-bottom: 5px;">Languages</h3>
            <div style="font-size: 10.5pt; display: flex; gap: 20px;">
                <?php foreach($extra['languages'] as $l): ?>
                    <div><strong><?php echo htmlspecialchars($l['name']); ?></strong>: <?php echo htmlspecialchars($l['level']); ?></div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if(!empty($extra['academic_projects'])): ?>
        <!-- Academic Projects -->
        <div style="margin-bottom: 25px;">
            <h3 style="font-size: 12pt; font-weight: 700; text-transform: uppercase; margin: 0 0 10px 0; border-bottom: 1px solid #ccc; padding-bottom: 5px;">Academic Projects</h3>
            <?php foreach($extra['academic_projects'] as $ap): ?>
            <div style="margin-bottom: 10px;">
                <div style="font-weight: 700; font-size: 11pt;">
                    <?php echo htmlspecialchars($ap['title']); ?>
                    <?php if(!empty($ap['year'])): ?>
                        <span style="font-weight: 400; color: #666;"> (<?php echo htmlspecialchars($ap['year']); ?>)</span>
                    <?php endif; ?>
                </div>
                <div style="font-size: 10.5pt; line-height: 1.4; color: #444;"><?php echo htmlspecialchars($ap['desc']); ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if(!empty($extra['tech_skills'])): ?>
        <!-- Technical Skills (Detailed) -->
        <div style="margin-bottom: 25px;">
            <h3 style="font-size: 12pt; font-weight: 700; text-transform: uppercase; margin: 0 0 10px 0; border-bottom: 1px solid #ccc; padding-bottom: 5px;">Technical Expertise</h3>
            <ul style="margin: 0; padding-left: 20px; font-size: 10.5pt; line-height: 1.5;">
                <?php foreach($extra['tech_skills'] as $ts): ?>
                <li style="margin-bottom: 5px;">
                    <strong><?php echo htmlspecialchars($ts['title']); ?></strong>: <?php echo htmlspecialchars($ts['desc']); ?>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <?php if(!empty($extra['abilities'])): ?>
        <!-- Abilities -->
        <div style="margin-bottom: 25px;">
            <h3 style="font-size: 12pt; font-weight: 700; text-transform: uppercase; margin: 0 0 10px 0; border-bottom: 1px solid #ccc; padding-bottom: 5px;">Abilities & Competencies</h3>
            <ul style="margin: 0; padding-left: 20px; font-size: 10.5pt; line-height: 1.5;">
                <?php foreach($extra['abilities'] as $ab): ?>
                <li style="margin-bottom: 8px;">
                    <strong><?php echo htmlspecialchars($ab['title']); ?></strong>
                    <div style="color: #555; margin-top: 2px;"><?php echo htmlspecialchars($ab['desc']); ?></div>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

    </div>
</div>
