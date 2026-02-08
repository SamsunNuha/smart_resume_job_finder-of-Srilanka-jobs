<?php
// user/templates/executive_pro.php
// Template 3: Executive Pro (Formerly the 'Elite' design)
// Elegant business layout, professional typography
?>
<div id="resume-a4" class="a4-page template-3" style="display: flex; flex-direction: column; font-family: 'Inter', sans-serif;">
    
    <?php
    $initials = '';
    if (!empty($resume['full_name'])) {
        $words = explode(' ', $resume['full_name']);
        foreach ($words as $w) { $initials .= strtoupper(substr($w, 0, 1)); }
        $initials = substr($initials, 0, 2);
    }
    ?>
    <div style="padding: 40px 40px 25px 40px; display: flex; justify-content: space-between; align-items: flex-start; background: white; gap: 20px;">
        <div style="text-align: left; flex: 1;">
            <h1 style="font-size: 38pt; font-weight: 300; color: #1e293b; margin: 0; letter-spacing: 5px; line-height: 1.1;">
                <?php echo strtoupper(htmlspecialchars($resume['full_name'])); ?>
            </h1>
            <?php if($extra['job_title'] ?? ''): ?>
                <div style="font-size: 14pt; color: #64748b; font-weight: 500; letter-spacing: 3px; margin-top: 15px;">
                    <?php echo strtoupper(htmlspecialchars($extra['job_title'])); ?>
                </div>
            <?php endif; ?>
            <?php if(!empty($extra['highest_degree'])): ?>
                <div style="font-size: 11pt; color: #94a3b8; font-weight: 400; letter-spacing: 2px; margin-top: 8px;">
                    <?php echo strtoupper(htmlspecialchars($extra['highest_degree'])); ?>
                </div>
            <?php endif; ?>
            
            <?php if(($extra['market1'] ?? '') || ($extra['market2'] ?? '')): ?>
                <div style="display: flex; gap: 20px; margin-top: 12px; font-size: 9pt; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">
                    <?php if($extra['market1'] ?? ''): ?>
                        <span>🎯 Market I: <?php echo htmlspecialchars($extra['market1']); ?></span>
                    <?php endif; ?>
                    <?php if($extra['market2'] ?? ''): ?>
                        <span>🎯 Market II: <?php echo htmlspecialchars($extra['market2']); ?></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div style="flex-shrink: 0; width: 120px;">
            <?php if (!empty($resume['photo'])): ?>
                <img src="<?php echo (strpos($resume['photo'], 'http') === 0) ? $resume['photo'] : '../uploads/resumes/' . $resume['photo']; ?>" 
                     style="width: 120px; height: 120px; border-radius: 8px; object-fit: cover; border: 4px solid #f1f5f9; box-shadow: 0 4px 12px rgba(0,0,0,0.1); display: block;">
            <?php else: ?>
                <div style="width: 120px; height: 120px; background: #334155; color: white; display: flex; align-items: center; justify-content: center; font-size: 34pt; font-weight: 700; border-radius: 8px; border: 4px solid #f1f5f9; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                    <?php echo htmlspecialchars($initials); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div style="background: #f8fafc; color: #64748b; padding: 10px 30px; display: flex; flex-wrap: wrap; justify-content: center; gap: 12px; font-size: 8pt; font-weight: 500; border-bottom: 1px solid #e2e8f0; align-items: center;">
        <span style="display: flex; align-items: center; gap: 6px;"><i class="fa-solid fa-phone" style="font-size: 8.5pt; color: #64748b;"></i> <?php echo htmlspecialchars($resume['phone']); ?></span>
        <span style="color: #cbd5e1;">•</span>
        <a href="mailto:<?php echo htmlspecialchars($resume['email']); ?>" style="color: #2563eb; text-decoration: none; display: flex; align-items: center; gap: 4px;"><i class="fa-solid fa-envelope" style="font-size: 8pt; color: #2563eb;"></i> <?php echo htmlspecialchars($resume['email']); ?></a>
        
        <?php if($extra['github'] ?? ''): ?>
            <span style="color: #cbd5e1;">•</span>
            <a href="https://github.com/<?php echo htmlspecialchars($extra['github']); ?>" target="_blank" style="color: #2563eb; text-decoration: none; display: flex; align-items: center; gap: 6px;"><i class="fa-brands fa-github" style="font-size: 9.5pt; color: #000;"></i> github.com/<?php echo htmlspecialchars($extra['github']); ?></a>
        <?php endif; ?>
        
        <?php if($extra['linkedin'] ?? ''): ?>
            <span style="color: #cbd5e1;">•</span>
            <a href="https://linkedin.com/in/<?php echo htmlspecialchars($extra['linkedin']); ?>" target="_blank" style="color: #2563eb; text-decoration: none; display: flex; align-items: center; gap: 6px;"><i class="fa-brands fa-linkedin" style="font-size: 9.5pt; color: #0077B5;"></i> linkedin.com/in/<?php echo htmlspecialchars($extra['linkedin']); ?></a>
        <?php endif; ?>
        
        <?php if($extra['portfolio_main'] ?? ''): ?>
            <span style="color: #cbd5e1;">•</span>
            <a href="<?php echo htmlspecialchars($extra['portfolio_main']); ?>" target="_blank" style="color: #2563eb; text-decoration: none; display: flex; align-items: center; gap: 6px;"><i class="fa-solid fa-globe" style="font-size: 8.5pt; color: #2563eb;"></i> Portfolio</a>
        <?php endif; ?>
        
        <span style="color: #cbd5e1;">•</span>
        <span style="display: flex; align-items: center; gap: 6px;"><i class="fa-solid fa-location-dot" style="font-size: 8.5pt; color: #64748b;"></i> <?php echo htmlspecialchars($resume['address']); ?></span>
    </div>

    <!-- Two Column Body -->
    <div style="display: flex; flex: 1;">
        
        <!-- Left Sidebar (Gray) -->
        <div style="width: 32%; background: #f1f5f9; padding: 35px 30px; border-right: 1px solid #e2e8f0; display: flex; flex-direction: column; gap: 35px;">
            

            <!-- Education -->
            <div>
                <h3 style="font-size: 13pt; font-weight: 800; color: #334155; border-bottom: 2px solid #cbd5e1; padding-bottom: 8px; margin-bottom: 25px; letter-spacing: 2px;">EDUCATION</h3>
                <?php foreach ($edu as $e): ?>
                <div style="margin-bottom: 25px;">
                    <div style="font-weight: 700; font-size: 10.5pt; color: #1e293b;"><?php echo htmlspecialchars($e['degree']); ?></div>
                    <div style="font-size: 9.5pt; color: #64748b; margin-top: 4px; font-weight: 600;"><?php echo htmlspecialchars($e['school']); ?> | <?php echo htmlspecialchars($e['year'] ?? ''); ?></div>
                    <?php if(!empty($e['gpa'])): ?>
                        <div style="font-size: 9pt; color: #1e293b; margin-top: 5px;"><strong>Current GPA:</strong> <?php echo htmlspecialchars($e['gpa']); ?></div>
                    <?php endif; ?>
                    <?php if(!empty($e['subjects'])): ?>
                        <div style="font-size: 8.5pt; color: #64748b; margin-top: 5px; line-height: 1.4;"><strong>Key Subjects:</strong> <?php echo htmlspecialchars($e['subjects']); ?></div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Skills with Progress Bars -->
            <div>
                <h3 style="font-size: 13pt; font-weight: 800; color: #334155; border-bottom: 2px solid #cbd5e1; padding-bottom: 10px; margin-bottom: 25px; letter-spacing: 2px;">SKILLS</h3>
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <?php 
                    $skills = array_filter(explode(',', $resume['skills']));
                    foreach ($skills as $index => $skill): 
                        // Fake percentage
                        $percent = 95 - ($index * 5);
                        if($percent < 60) $percent = 75;
                    ?>
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        <div style="display: flex; justify-content: space-between; font-size: 9.5pt; color: #475569; font-weight: 600;">
                            <span><?php echo trim($skill); ?></span>
                        </div>
                        <div style="height: 6px; background: #e2e8f0; border-radius: 10px; width: 100%;">
                            <div style="height: 100%; background: #334155; width: <?php echo $percent; ?>%; border-radius: 10px;"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Languages -->
            <?php if(!empty($extra['languages'])): ?>
            <div>
                <h3 style="font-size: 13pt; font-weight: 800; color: #334155; border-bottom: 2px solid #cbd5e1; padding-bottom: 10px; margin-bottom: 25px; letter-spacing: 2px;">LANGUAGES</h3>
                <div style="display: flex; flex-direction: column; gap: 12px; font-size: 9.5pt; color: #475569; font-weight: 600;">
                    <?php foreach($extra['languages'] as $l): ?>
                        <div style="display: flex; justify-content: space-between;">
                            <span><?php echo htmlspecialchars($l['name']); ?></span>
                            <span style="color: #64748b; font-weight: 400;"><?php echo htmlspecialchars($l['level']); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Right Main Content -->
        <div style="width: 68%; padding: 35px 40px; display: flex; flex-direction: column; gap: 40px; background: white;">
            
            <!-- Summary -->
            <div>
                <h3 style="font-size: 14pt; font-weight: 800; color: #1e293b; margin-bottom: 20px; letter-spacing: 1px;">PROFESSIONAL SUMMARY</h3>
                <p style="font-size: 10.5pt; line-height: 1.8; text-align: justify; color: #334155;">
                    <?php echo nl2br(htmlspecialchars($resume['summary'])); ?>
                </p>
            </div>

            <!-- Experience -->
            <div>
                <h3 style="font-size: 14pt; font-weight: 800; color: #1e293b; margin-bottom: 30px; letter-spacing: 1px;">WORK EXPERIENCE</h3>
                <div style="display: flex; flex-direction: column; gap: 35px;">
                    <?php foreach ($exp as $x): ?>
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 8px;">
                            <h4 style="font-size: 11.5pt; font-weight: 800; color: #0f172a; margin: 0;"><?php echo strtoupper(htmlspecialchars($x['role'])); ?></h4>
                            <span style="font-size: 9.5pt; color: #64748b; font-weight: 600;"><?php echo strtoupper(htmlspecialchars($x['duration'] ?? 'Present')); ?></span>
                        </div>
                        <div style="font-weight: 600; color: #1d4ed8; font-size: 10.5pt; margin-bottom: 12px;"><?php echo htmlspecialchars($x['company']); ?></div>
                        <div style="font-size: 10.5pt; line-height: 1.7; color: #334155;">
                            <?php echo nl2br(htmlspecialchars($x['desc'] ?? '')); ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Academic Projects -->
            <?php if (!empty($extra['academic_projects'])): ?>
            <div>
                <h3 style="font-size: 14pt; font-weight: 800; color: #1e293b; margin-bottom: 25px; letter-spacing: 1px;">ACADEMIC PROJECTS</h3>
                <div style="display: flex; flex-direction: column; gap: 25px;">
                    <?php foreach ($extra['academic_projects'] as $ap): ?>
                    <div>
                        <div style="font-weight: 700; color: #0f172a; font-size: 11.5pt; margin-bottom: 8px;"><?php echo htmlspecialchars($ap['title']); ?> <?php if(!empty($ap['year'])): ?><span style="color: #64748b; font-weight: 400; font-size: 9pt;">(<?php echo htmlspecialchars($ap['year']); ?>)</span><?php endif; ?></div>
                        <div style="font-size: 10.5pt; color: #334155; line-height: 1.6;"><?php echo htmlspecialchars($ap['desc']); ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Projects -->
            <?php if (!empty($proj)): ?>
            
            <!-- Certifications -->
            <?php if(!empty($cert)): ?>
            <div>
                <h3 style="font-size: 14pt; font-weight: 800; color: #1e293b; margin-bottom: 25px; letter-spacing: 1px;">CERTIFICATIONS</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <?php foreach($cert as $c): ?>
                    <div style="padding: 15px; border: 1px solid #e2e8f0; border-radius: 8px;">
                        <div style="font-weight: 700; color: #0f172a; font-size: 10.5pt;"><?php echo htmlspecialchars($c['name']); ?> <?php if(!empty($c['year'])): ?><span style="color: #64748b; font-weight: 400; font-size: 9pt;">(<?php echo htmlspecialchars($c['year']); ?>)</span><?php endif; ?></div>
                        <div style="font-size: 9.5pt; color: #64748b; margin-top: 5px;"><?php echo htmlspecialchars($c['issuer']); ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Achievements -->
            <?php if(!empty($extra['achievements'])): ?>
            <div>
                <h3 style="font-size: 14pt; font-weight: 800; color: #1e293b; margin-bottom: 25px; letter-spacing: 1px;">KEY ACHIEVEMENTS</h3>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <?php foreach($extra['achievements'] as $a): ?>
                    <div style="display: flex; gap: 12px; align-items: center; font-size: 10.5pt; color: #334155;">
                        <span style="color: #1e293b;">◈</span>
                        <?php echo htmlspecialchars($a); ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            <div>
                <h3 style="font-size: 14pt; font-weight: 800; color: #1e293b; margin-bottom: 25px; letter-spacing: 1px;">NOTABLE PROJECTS</h3>
                <div style="display: grid; grid-template-columns: 1fr; gap: 25px;">
                    <?php foreach ($proj as $p): ?>
                    <div style="padding: 20px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;">
                        <div style="font-weight: 700; color: #0f172a; margin-bottom: 8px; font-size: 11pt;"><?php echo htmlspecialchars($p['name']); ?></div>
                        <div style="font-size: 10pt; color: #475569; line-height: 1.6;"><?php echo htmlspecialchars($p['description'] ?? ''); ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Technical Expertise -->
            <?php if(!empty($extra['tech_skills'])): ?>
            <div>
                <h3 style="font-size: 14pt; font-weight: 800; color: #1e293b; margin-bottom: 25px; letter-spacing: 1px;">TECHNICAL EXPERTISE</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <?php foreach($extra['tech_skills'] as $ts): ?>
                    <div style="border-left: 3px solid #1e293b; padding-left: 15px; position: relative;">
                        <span style="position: absolute; left: -10px; color: #1e293b;">•</span>
                        <div style="font-weight: 700; color: #0f172a; font-size: 11pt;"><?php echo htmlspecialchars($ts['title']); ?></div>
                        <div style="font-size: 9.5pt; color: #475569; margin-top: 5px;"><?php echo htmlspecialchars($ts['desc']); ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Abilities -->
            <?php if(!empty($extra['abilities'])): ?>
            <div>
                <h3 style="font-size: 14pt; font-weight: 800; color: #1e293b; margin-bottom: 25px; letter-spacing: 1px;">ABILITIES & COMPETENCIES</h3>
                <div style="display: grid; grid-template-columns: 1fr; gap: 15px;">
                    <?php foreach($extra['abilities'] as $ab): ?>
                    <div style="display: flex; gap: 15px; align-items: flex-start;">
                        <span style="color: #1e293b; font-size: 12pt;">⚡</span>
                        <div>
                            <div style="font-weight: 700; color: #0f172a; font-size: 10.5pt;">• <?php echo htmlspecialchars($ab['title']); ?></div>
                            <div style="font-size: 9.5pt; color: #475569; margin-top: 4px;"><?php echo htmlspecialchars($ab['desc']); ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Bank Details for Pro -->
            <?php if ($is_pro && $resume['bank_name']): ?>
                <div style="margin-top: 20px; background: #fdf2f8; border: 1px solid #fbcfe8; padding: 25px; border-radius: 12px;">
                    <h3 style="font-size: 11pt; font-weight: 800; color: #9d174d; margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                        🏦 TRANSACTIONAL VERIFICATION (PRO)
                    </h3>
                    <div style="font-size: 10pt; display: grid; grid-template-columns: 1fr 1fr; gap: 15px; color: #831843;">
                        <div><strong>Bank:</strong> <?php echo htmlspecialchars($resume['bank_name']); ?></div>
                        <div><strong>Account:</strong> <?php echo htmlspecialchars($resume['acc_no']); ?></div>
                        <div style="grid-column: span 2; font-size: 8.5pt; color: #be185d; border-top: 1px solid #fbcfe8; padding-top: 15px; margin-top: 5px; font-weight: 600;">
                            ✓ Central Bank Verified for Professional Remittance
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
