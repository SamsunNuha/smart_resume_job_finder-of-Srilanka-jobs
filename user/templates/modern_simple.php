<?php
// user/templates/modern_simple.php
// Template 2: Modern Simple
// Blue Header, Two Column, clean icons
?>
<div id="resume-a4" class="a4-page" style="display: flex; flex-direction: column;">
    
    <!-- Blue Header Bar -->
    <div style="background: #2563eb; color: white; padding: 25px 50px; display: flex; align-items: flex-start; justify-content: space-between; gap: 20px;">
        <div style="flex: 1;">
            <h1 style="font-size: 32pt; font-weight: 700; margin: 0; line-height: 1; text-transform: uppercase;">
                <?php echo htmlspecialchars($resume['full_name']); ?>
            </h1>
            <?php if($extra['job_title'] ?? ''): ?>
                <div style="font-size: 14pt; font-weight: 300; margin-top: 5px; opacity: 0.9;">
                    <?php echo htmlspecialchars($extra['job_title']); ?>
                </div>
            <?php endif; ?>
            <?php if(!empty($extra['highest_degree'])): ?>
                <div style="font-size: 10.5pt; font-weight: 300; margin-top: 5px; opacity: 0.8; font-style: italic;">
                    <?php echo htmlspecialchars($extra['highest_degree']); ?>
                </div>
            <?php endif; ?>
        </div>
        
        <?php
        $initials = '';
        if (!empty($resume['full_name'])) {
            $words = explode(' ', $resume['full_name']);
            foreach ($words as $w) { $initials .= strtoupper(substr($w, 0, 1)); }
            $initials = substr($initials, 0, 2);
        }
        ?>
        <div style="flex-shrink: 0; width: 100px;">
<?php if (!empty($resume['photo'])): ?>
                <img src="<?php echo (strpos($resume['photo'], 'http') === 0) ? $resume['photo'] : '../uploads/resumes/' . $resume['photo']; ?>" 
                     style="width: 100px; height: 100px; border-radius: 4px; border: 3px solid rgba(255,255,255,0.5); object-fit: cover; display: bock;">
            <?php else: ?>
                <div style="width: 100px; height: 100px; background: rgba(255,255,255,0.2); color: white; display: flex; align-items: center; justify-content: center; font-size: 28pt; font-weight: 700; border: 3px solid rgba(255,255,255,0.5); border-radius: 4px;">
                    <?php echo htmlspecialchars($initials); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Contact Bar (Refined for Blue Clickable Links) -->
    <div style="background: #f8fafc; color: #1e40af; padding: 10px 30px; font-size: 8pt; display: flex; flex-wrap: wrap; justify-content: flex-start; gap: 8px; font-weight: 500; border-bottom: 1px solid #e2e8f0; align-items: center;">
        <span style="color: #475569;"><i class="fa-solid fa-phone" style="font-size: 8pt; color: #1e40af;"></i> <?php echo htmlspecialchars($resume['phone']); ?></span>
        <span style="color: #cbd5e1;">•</span>
        <a href="mailto:<?php echo htmlspecialchars($resume['email']); ?>" style="color: #2563eb; text-decoration: none; display: flex; align-items: center; gap: 4px;"><i class="fa-solid fa-envelope" style="font-size: 8pt; color: #2563eb;"></i> <?php echo htmlspecialchars($resume['email']); ?></a>
        <span style="color: #cbd5e1;">•</span>
        <span style="color: #475569; display: flex; align-items: center; gap: 4px;"><i class="fa-solid fa-location-dot" style="font-size: 8pt; color: #1e40af;"></i> <?php echo htmlspecialchars($resume['address']); ?></span>
        <?php if(!empty($extra['github'])): ?>
            <span style="color: #cbd5e1;">•</span>
            <a href="https://github.com/<?php echo htmlspecialchars($extra['github']); ?>" target="_blank" style="color: #2563eb; text-decoration: none; display: flex; align-items: center; gap: 4px;"><i class="fa-brands fa-github" style="font-size: 9pt; color: #000;"></i> github.com/<?php echo htmlspecialchars($extra['github']); ?></a>
        <?php endif; ?>
        <?php if(!empty($extra['linkedin'])): ?>
            <span style="color: #cbd5e1;">•</span>
            <a href="https://linkedin.com/in/<?php echo htmlspecialchars($extra['linkedin']); ?>" target="_blank" style="color: #2563eb; text-decoration: none; display: flex; align-items: center; gap: 4px;"><i class="fa-brands fa-linkedin" style="font-size: 9pt; color: #0077B5;"></i> linkedin.com/in/<?php echo htmlspecialchars($extra['linkedin']); ?></a>
        <?php endif; ?>
    </div>

    <!-- Two Column Body -->
    <div style="display: flex; flex: 1; padding: 25px 40px;">
        
        <!-- Left Sidebar Main (Skills & Educ) -->
        <div style="width: 32%; padding-right: 30px; border-right: 1px solid #e5e7eb;">
            
            <!-- Education -->
            <div style="margin-bottom: 40px;">
                <h3 style="font-size: 11pt; font-weight: 700; color: #2563eb; text-transform: uppercase; margin-bottom: 20px; border-bottom: 2px solid #2563eb; padding-bottom: 5px;">Education</h3>
                <?php foreach ($edu as $e): ?>
                <div style="margin-bottom: 20px;">
                         <div style="font-weight: 700; color: #111; font-size: 9.5pt;"><?php echo htmlspecialchars($e['degree']); ?></div>
                         <div style="font-size: 9pt; color: #1d4ed8; font-weight: 600;"><?php echo htmlspecialchars($e['school']); ?></div>
                         <?php if(!empty($e['gpa'])): ?>
                          <div style="font-size: 9pt; color: #111; margin-top: 3px;"><strong>Current GPA:</strong> <?php echo htmlspecialchars($e['gpa']); ?></div>
                         <?php endif; ?>
                         <?php if(!empty($e['subjects'])): ?>
                             <div style="font-size: 8pt; color: #4b5563; margin-top: 3px; line-height: 1.3;"><strong>Key Subjects:</strong> <?php echo htmlspecialchars($e['subjects']); ?></div>
                         <?php endif; ?>
                         <div style="font-size: 8pt; color: #1d4ed8; margin-top: 3px; font-weight: 500;">Year: <?php echo htmlspecialchars($e['year'] ?? ''); ?></div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Skills -->
            <div style="margin-bottom: 40px;">
                <h3 style="font-size: 11pt; font-weight: 700; color: #2563eb; text-transform: uppercase; margin-bottom: 20px; border-bottom: 2px solid #2563eb; padding-bottom: 5px;">Skills</h3>
                <ul style="list-style: none; padding: 0; margin: 0; font-size: 10pt; line-height: 1.8; color: #374151;">
                    <?php 
                    $skills = explode(',', $resume['skills']);
                    foreach($skills as $skill): if(trim($skill)):
                    ?>
                        <li style="display: flex; align-items: center; gap: 8px;">
                            <span style="color: #2563eb;">✓</span> <?php echo htmlspecialchars(trim($skill)); ?>
                        </li>
                    <?php endif; endforeach; ?>
                </ul>
            </div>

            <!-- Languages -->
            <?php if(!empty($extra['languages'])): ?>
            <div style="margin-bottom: 40px;">
                <h3 style="font-size: 11pt; font-weight: 700; color: #2563eb; text-transform: uppercase; margin-bottom: 20px; border-bottom: 2px solid #2563eb; padding-bottom: 5px;">Languages</h3>
                <div style="font-size: 10pt; color: #374151; display:flex; flex-direction:column; gap:8px;">
                    <?php foreach($extra['languages'] as $l): ?>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span><?php echo htmlspecialchars($l['name']); ?></span>
                            <span style="background: #eff6ff; color: #1e40af; padding: 2px 8px; border-radius: 10px; font-size: 8pt; font-weight: 600;"><?php echo htmlspecialchars($l['level']); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Technical Expertise -->
            <?php if(!empty($extra['tech_skills'])): ?>
            <div style="margin-bottom: 40px;">
                <h3 style="font-size: 11pt; font-weight: 700; color: #2563eb; text-transform: uppercase; margin-bottom: 20px; border-bottom: 2px solid #2563eb; padding-bottom: 5px;">Technical Expertise</h3>
                <ul style="padding: 0; margin: 0; list-style: none; font-size: 10pt; color: #374151; display:flex; flex-direction:column; gap:12px;">
                    <?php foreach($extra['tech_skills'] as $ts): ?>
                        <li style="display: flex; gap: 10px;">
                            <span style="color: #2563eb;">•</span>
                            <div>
                                <div style="font-weight: 700; color: #1e40af;"><?php echo htmlspecialchars($ts['title']); ?></div>
                                <div style="font-size: 9pt; color: #6b7280; margin-top: 2px;"><?php echo htmlspecialchars($ts['desc']); ?></div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <!-- Abilities -->
            <?php if(!empty($extra['abilities'])): ?>
            <div style="margin-bottom: 40px;">
                <h3 style="font-size: 11pt; font-weight: 700; color: #2563eb; text-transform: uppercase; margin-bottom: 20px; border-bottom: 2px solid #2563eb; padding-bottom: 5px;">Abilities</h3>
                <ul style="padding: 0; margin: 0; list-style: none; font-size: 10pt; color: #374151; display:flex; flex-direction:column; gap:10px;">
                    <?php foreach($extra['abilities'] as $ab): ?>
                        <li style="display: flex; gap: 8px;">
                            <span style="color: #2563eb;">⚡</span>
                            <div>
                                <div style="font-weight: 600;"><?php echo htmlspecialchars($ab['title']); ?></div>
                                <div style="font-size: 9pt; color: #6b7280;"><?php echo htmlspecialchars($ab['desc']); ?></div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
        </div>

        <!-- Right Content Main (Summary & Exp) -->
        <div style="width: 68%; padding-left: 30px;">
            
            <!-- Summary -->
            <div style="margin-bottom: 40px;">
                <h3 style="font-size: 11pt; font-weight: 700; color: #2563eb; text-transform: uppercase; margin-bottom: 15px; border-bottom: 2px solid #2563eb; padding-bottom: 5px;">Profile</h3>
                <p style="font-size: 10.5pt; line-height: 1.6; color: #374151; text-align: justify;">
                    <?php echo nl2br(htmlspecialchars($resume['summary'])); ?>
                </p>
            </div>

            <!-- Experience -->
            <div>
                <h3 style="font-size: 11pt; font-weight: 700; color: #2563eb; text-transform: uppercase; margin-bottom: 25px; border-bottom: 2px solid #2563eb; padding-bottom: 5px;">Experience</h3>
                
                <?php foreach ($exp as $x): ?>
                <div style="margin-bottom: 30px;">
                         <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 5px;">
                             <div style="font-weight: 700; font-size: 11pt; color: #000;"><?php echo htmlspecialchars($x['role']); ?></div>
                             <div style="font-size: 9pt; color: #1d4ed8; font-weight: 600;"><?php echo htmlspecialchars($x['duration'] ?? 'Present'); ?></div>
                         </div>
                         <div style="color: #4b5563; font-weight: 600; font-size: 9.5pt; margin-bottom: 8px;">
                             <?php echo htmlspecialchars($x['company']); ?>
                         </div>
                    <div style="font-size: 10.5pt; line-height: 1.6; color: #374151;">
                         <?php echo nl2br(htmlspecialchars($x['desc'] ?? '')); ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Academic Projects -->
            <?php if (!empty($extra['academic_projects'])): ?>
            <div style="margin-top: 30px; margin-bottom: 30px;">
                <h3 style="font-size: 11pt; font-weight: 700; color: #2563eb; text-transform: uppercase; margin-bottom: 20px; border-bottom: 2px solid #2563eb; padding-bottom: 5px;">Academic Projects</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <?php foreach ($extra['academic_projects'] as $ap): ?>
                        <div style="background: #f0f9ff; padding: 15px; border-radius: 8px; border: 1px solid #bae6fd;">
                            <div style="font-weight: 700; color: #0369a1; margin-bottom: 5px;"><?php echo htmlspecialchars($ap['title']); ?> <?php if(!empty($ap['year'])): ?><span style="color: #64748b; font-weight: 400; font-size: 8.5pt;">(<?php echo htmlspecialchars($ap['year']); ?>)</span><?php endif; ?></div>
                            <div style="font-size: 9.5pt; color: #475569; line-height: 1.4;"><?php echo htmlspecialchars($ap['desc'] ?? ''); ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Projects -->
            <?php if (!empty($proj)): ?>
            <div style="margin-top: 30px; margin-bottom: 30px;">
                <h3 style="font-size: 11pt; font-weight: 700; color: #2563eb; text-transform: uppercase; margin-bottom: 20px; border-bottom: 2px solid #2563eb; padding-bottom: 5px;">Projects</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <?php foreach ($proj as $p): ?>
                        <div style="background: #f8fafc; padding: 15px; border-radius: 8px;">
                            <div style="font-weight: 700; color: #1f2937; margin-bottom: 5px;"><?php echo htmlspecialchars($p['name']); ?></div>
                            <div style="font-size: 9.5pt; color: #64748b; line-height: 1.4;"><?php echo htmlspecialchars($p['description'] ?? ''); ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if(!empty($cert)): ?>
            <!-- Certifications -->
            <div style="margin-bottom: 30px;">
                <h3 style="font-size: 11pt; font-weight: 700; color: #2563eb; text-transform: uppercase; margin-bottom: 20px; border-bottom: 2px solid #2563eb; padding-bottom: 5px;">Certifications</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <?php foreach($cert as $c): ?>
                    <div style="border-left: 3px solid #2563eb; padding-left: 12px;">
                        <div style="font-weight: 700; font-size: 10pt;"><?php echo htmlspecialchars($c['name']); ?> <?php if(!empty($c['year'])): ?><span style="color: #6b7280; font-weight: 400; font-size: 9pt;">(<?php echo htmlspecialchars($c['year']); ?>)</span><?php endif; ?></div>
                        <?php if(!empty($c['issuer'])): ?>
                            <div style="font-size: 9pt; color: #6b7280;"><?php echo htmlspecialchars($c['issuer']); ?></div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if(!empty($extra['achievements'])): ?>
            <!-- Achievements -->
            <div style="margin-bottom: 30px;">
                <h3 style="font-size: 11pt; font-weight: 700; color: #2563eb; text-transform: uppercase; margin-bottom: 20px; border-bottom: 2px solid #2563eb; padding-bottom: 5px;">Key Achievements</h3>
                <ul style="padding-left: 20px; margin: 0; font-size: 10pt; color: #374151; line-height: 1.6;">
                    <?php foreach($extra['achievements'] as $a): ?>
                        <li style="margin-bottom: 5px;"><?php echo htmlspecialchars($a); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
