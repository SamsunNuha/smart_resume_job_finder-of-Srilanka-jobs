<?php
// user/templates/ai_smart.php
// Template 6: AI Smart Professional
// Clean corporate, Achievement Highlight, Teal theme
?>
<div id="resume-a4" class="a4-page template-6" style="display: flex; flex-direction: column; font-family: 'Inter', sans-serif;">
    
    <!-- Top Header -->
    <div style="padding: 50px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e5e7eb;">
    <?php
    $initials = '';
    if (!empty($resume['full_name'])) {
        $words = explode(' ', $resume['full_name']);
        foreach ($words as $w) { $initials .= strtoupper(substr($w, 0, 1)); }
        $initials = substr($initials, 0, 2);
    }
    ?>
    <!-- Top Header -->
    <div style="padding: 30px 50px; display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 1px solid #e5e7eb; gap: 20px;">
        <div style="flex: 1;">
            <h1 style="font-size: 36pt; font-weight: 800; color: #111827; margin: 0; line-height: 1;">
                <?php echo htmlspecialchars($resume['full_name']); ?>
            </h1>
            <div style="font-size: 16pt; color: #0d9488; font-weight: 600; margin-top: 10px;">
                 <?php echo htmlspecialchars($extra['job_title'] ?? 'Senior Professional'); ?>
            </div>
            <?php if(!empty($extra['highest_degree'])): ?>
                <div style="font-size: 11pt; color: #6b7280; font-weight: 500; margin-top: 5px;">
                    🎓 <?php echo htmlspecialchars($extra['highest_degree']); ?>
                </div>
            <?php endif; ?>
            <div style="margin-top: 10px; text-align: left; font-size: 8.5pt; color: #4b5563; line-height: 1.6; display: flex; flex-direction: column; gap: 4px;">
                 <div style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center; margin-top: 2px;">
                     <span style="display: flex; align-items: center; gap: 4px;"><i class="fa-solid fa-phone" style="font-size: 8.5pt; color: #0d9488;"></i> <?php echo htmlspecialchars($resume['phone']); ?></span>
                     <span style="color: #e5e7eb;">•</span>
                     <a href="mailto:<?php echo htmlspecialchars($resume['email']); ?>" style="color: #2563eb; text-decoration: none; display: flex; align-items: center; gap: 4px;"><i class="fa-solid fa-envelope" style="font-size: 8.5pt; color: #2563eb;"></i> <?php echo htmlspecialchars($resume['email']); ?></a>
                     <?php if(!empty($extra['github'])): ?>
                        <span style="color: #e5e7eb;">•</span>
                        <a href="https://github.com/<?php echo htmlspecialchars($extra['github']); ?>" target="_blank" style="color: #2563eb; text-decoration: none; display: flex; align-items: center; gap: 4px;"><i class="fa-brands fa-github" style="font-size: 9.5pt; color: #000;"></i> github/<?php echo htmlspecialchars($extra['github']); ?></a>
                     <?php endif; ?>
                     <?php if(!empty($extra['linkedin'])): ?>
                        <span style="color: #e5e7eb;">•</span>
                        <a href="https://linkedin.com/in/<?php echo htmlspecialchars($extra['linkedin']); ?>" target="_blank" style="color: #2563eb; text-decoration: none; display: flex; align-items: center; gap: 4px;"><i class="fa-brands fa-linkedin" style="font-size: 9.5pt; color: #0077B5;"></i> linkedin/<?php echo htmlspecialchars($extra['linkedin']); ?></a>
                     <?php endif; ?>
                 </div>
                 <div style="color: #6b7280; font-style: italic; font-size: 8.5pt; display: flex; align-items: center; gap: 4px;"><i class="fa-solid fa-location-dot" style="font-size: 8.5pt; color: #0d9488;"></i> <?php echo htmlspecialchars($resume['address']); ?></div>
            </div>
        </div>

        <div style="flex-shrink: 0; width: 120px;">
            <?php if (!empty($resume['photo'])): ?>
                <img src="<?php echo (strpos($resume['photo'], 'http') === 0) ? $resume['photo'] : '../uploads/resumes/' . $resume['photo']; ?>" 
                     style="width: 120px; height: 120px; border-radius: 8px; object-fit: cover; border: 4px solid #f3f4f6; box-shadow: 0 4px 10px rgba(0,0,0,0.05); display: block;">
            <?php else: ?>
                <div style="width: 120px; height: 120px; background: #0d9488; color: white; display: flex; align-items: center; justify-content: center; font-size: 36pt; font-weight: 700; border: 4px solid #f3f4f6; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                    <?php echo htmlspecialchars($initials); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    </div>

    <!-- AI Highlights Section (Unique to this template) -->
    <div style="background: #f0fdfa; padding: 25px 50px; border-bottom: 1px solid #ccfbf1;">
         <div style="display: flex; gap: 30px; align-items: center;">
            <div style="font-weight: 700; color: #0f766e; white-space: nowrap; font-size: 10pt;">✨ KEY HIGHLIGHTS</div>
            <div style="display: flex; gap: 20px; font-size: 10pt; color: #115e59; flex-wrap: wrap;">
                <?php 
                // Dynamically simulate highlights from description or skills if real AI wasn't used
                $skills = explode(',', $resume['skills']);
                $highlights = array_slice($skills, 0, 3);
                foreach($highlights as $hl): if(trim($hl)): 
                ?>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <span style="background: #0d9488; width: 6px; height: 6px; border-radius: 50%;"></span>
                        <?php echo trim($hl); ?> Expert
                    </div>
                <?php endif; endforeach; ?>
                 <div style="display: flex; align-items: center; gap: 6px;">
                    <span style="background: #0d9488; width: 6px; height: 6px; border-radius: 50%;"></span>
                    5+ Years Experience
                </div>
            </div>
         </div>
    </div>

    <div style="display: flex; flex: 1; padding: 30px 50px;">
        
        <!-- Left Main -->
        <div style="width: 65%; padding-right: 50px;">
            
            <div style="margin-bottom: 40px;">
                <h3 style="font-size: 12pt; font-weight: 700; color: #111827; letter-spacing: 1px; margin-bottom: 15px;">PROFESSIONAL PROFILE</h3>
                <p style="font-size: 10.5pt; color: #374151; line-height: 1.7; text-align: justify;">
                    <?php echo nl2br(htmlspecialchars($resume['summary'])); ?>
                </p>
            </div>

            <div style="margin-bottom: 40px;">
                 <h3 style="font-size: 12pt; font-weight: 700; color: #111827; letter-spacing: 1px; margin-bottom: 25px;">WORK EXPERIENCE</h3>
                 
                 <?php foreach ($exp as $x): ?>
                 <div style="margin-bottom: 30px;">
                     <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 6px;">
                         <div style="font-weight: 700; font-size: 12pt; color: #000;"><?php echo htmlspecialchars($x['role']); ?></div>
                     </div>
                     <div style="color: #0d9488; font-weight: 600; font-size: 10pt; margin-bottom: 10px;">
                         <?php echo htmlspecialchars($x['company']); ?> | <?php echo htmlspecialchars($x['duration'] ?? 'Present'); ?>
                     </div>
                     <div style="font-size: 10.5pt; color: #4b5563; line-height: 1.6;">
                         <?php echo nl2br(htmlspecialchars($x['desc'] ?? '')); ?>
                     </div>
                 </div>
                 <?php endforeach; ?>
            </div>

            <?php if (!empty($extra['academic_projects'])): ?>
            <div style="margin-bottom: 40px;">
                 <h3 style="font-size: 12pt; font-weight: 700; color: #111827; letter-spacing: 1px; margin-bottom: 25px;">ACADEMIC PROJECTS</h3>
                 <div style="display: flex; flex-direction: column; gap: 20px;">
                     <?php foreach ($extra['academic_projects'] as $ap): ?>
                        <div style="padding: 15px 20px; border-radius: 8px; background: #f8fafc; border-right: 4px solid #0d9488;">
                            <div style="font-weight: 700; color: #111827; margin-bottom: 5px;"><?php echo htmlspecialchars($ap['title']); ?> <?php if(!empty($ap['year'])): ?><span style="color: #0d9488; font-weight: 400; font-size: 9pt;">(<?php echo htmlspecialchars($ap['year']); ?>)</span><?php endif; ?></div>
                            <div style="font-size: 10pt; color: #4b5563; line-height: 1.5;"><?php echo htmlspecialchars($ap['desc']); ?></div>
                        </div>
                     <?php endforeach; ?>
                 </div>
            </div>
            <?php endif; ?>

             <?php if (!empty($proj)): ?>
             <div>
                 <h3 style="font-size: 12pt; font-weight: 700; color: #111827; letter-spacing: 1px; margin-bottom: 25px;">KEY ACHIEVEMENTS</h3>
                 <div style="display: grid; grid-template-columns: 1fr; gap: 20px;">
                     <?php foreach ($proj as $p): ?>
                        <div style="background: #f9fafb; padding: 15px 20px; border-radius: 8px; border-left: 4px solid #0d9488;">
                            <div style="font-weight: 700; color: #111827; margin-bottom: 5px;"><?php echo htmlspecialchars($p['name']); ?></div>
                            <div style="font-size: 10pt; color: #4b5563;"><?php echo htmlspecialchars($p['description'] ?? ''); ?></div>
                        </div>
                     <?php endforeach; ?>
                 </div>
             </div>
             <?php endif; ?>

        </div>

        <!-- Right Sidebar -->
        <div style="width: 35%; padding-left: 30px; border-left: 1px solid #e5e7eb;">
            
            <div style="margin-bottom: 40px;">
                 <h3 style="font-size: 11pt; font-weight: 700; color: #111827; margin-bottom: 20px;">SKILLS & TOOLS</h3>
                 <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                    <?php 
                    $skills = explode(',', $resume['skills']);
                    foreach($skills as $skill): if(trim($skill)):
                    ?>
                        <div style="padding: 5px 10px; background: #e0f2fe; color: #0369a1; font-size: 9pt; border-radius: 4px; font-weight: 500;">
                            <?php echo htmlspecialchars(trim($skill)); ?>
                        </div>
                    <?php endif; endforeach; ?>
                </div>
            </div>

            <div style="margin-bottom: 40px;">
                 <h3 style="font-size: 11pt; font-weight: 700; color: #111827; margin-bottom: 20px;">EDUCATION</h3>
                 <?php foreach ($edu as $e): ?>
                 <div style="margin-bottom: 20px;">
                      <div style="font-weight: 700; font-size: 10pt; color: #111827;"><?php echo htmlspecialchars($e['school']); ?></div>
                      <div style="font-size: 9.5pt; color: #0d9488; font-weight: 600;"><?php echo htmlspecialchars($e['degree']); ?></div>
                      <?php if(!empty($e['gpa'])): ?>
                       <div style="font-size: 9pt; color: #111; margin-top: 3px;"><strong>Current GPA:</strong> <?php echo htmlspecialchars($e['gpa']); ?></div>
                      <?php endif; ?>
                      <?php if(!empty($e['subjects'])): ?>
                          <div style="font-size: 8.5pt; color: #4b5563; margin-top: 3px; line-height: 1.3;"><strong>Key Subjects:</strong> <?php echo htmlspecialchars($e['subjects']); ?></div>
                      <?php endif; ?>
                      <div style="font-size: 8.5pt; color: #9ca3af; margin-top: 3px;">Year: <?php echo htmlspecialchars($e['year'] ?? ''); ?></div>
                 </div>
                 <?php endforeach; ?>
            </div>

            <?php if(!empty($cert)): ?>
            <div style="margin-bottom: 40px;">
                 <h3 style="font-size: 11pt; font-weight: 700; color: #111827; margin-bottom: 20px;">CERTIFICATIONS</h3>
                 <?php foreach($cert as $c): ?>
                 <div style="margin-bottom: 12px;">
                     <div style="font-weight: 700; font-size: 9.5pt; color: #111827;"><?php echo htmlspecialchars($c['name']); ?> <?php if(!empty($c['year'])): ?><span style="color: #6b7280; font-weight: 400; font-size: 8.5pt;">(<?php echo htmlspecialchars($c['year']); ?>)</span><?php endif; ?></div>
                     <?php if(!empty($c['issuer'])): ?>
                        <div style="font-size: 8.5pt; color: #0d9488;"><?php echo htmlspecialchars($c['issuer']); ?></div>
                     <?php endif; ?>
                 </div>
                 <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <?php if(!empty($extra['languages'])): ?>
            <div style="margin-bottom: 40px;">
                 <h3 style="font-size: 11pt; font-weight: 700; color: #111827; margin-bottom: 20px;">LANGUAGES</h3>
                 <div style="display: flex; flex-direction: column; gap: 10px;">
                    <?php foreach($extra['languages'] as $l): ?>
                        <div style="display: flex; justify-content: space-between; font-size: 9.5pt;">
                            <span style="font-weight: 600;"><?php echo htmlspecialchars($l['name']); ?></span>
                            <span style="color: #0d9488;"><?php echo htmlspecialchars($l['level']); ?></span>
                        </div>
                    <?php endforeach; ?>
                 </div>
            </div>
            <?php endif; ?>
            
            <?php if(!empty($extra['tech_skills'])): ?>
            <div style="margin-bottom: 40px;">
                 <h3 style="font-size: 11pt; font-weight: 700; color: #111827; margin-bottom: 20px;">TECHNICAL EXPERTISE</h3>
                 <ul style="padding: 0; margin: 0; list-style: none; display: flex; flex-direction: column; gap: 15px;">
                     <?php foreach($extra['tech_skills'] as $ts): ?>
                     <li style="display: flex; gap: 10px;">
                         <span style="color: #0d9488;">•</span>
                         <div>
                             <div style="font-weight: 700; font-size: 9.5pt; color: #111827;"><?php echo htmlspecialchars($ts['title']); ?></div>
                             <div style="font-size: 8.5pt; color: #4b5563; margin-top: 2px;"><?php echo htmlspecialchars($ts['desc']); ?></div>
                         </div>
                     </li>
                     <?php endforeach; ?>
                 </ul>
            </div>
            <?php endif; ?>

            <?php if(!empty($extra['abilities'])): ?>
            <div style="margin-bottom: 40px;">
                 <h3 style="font-size: 11pt; font-weight: 700; color: #111827; margin-bottom: 20px;">ABILITIES</h3>
                 <ul style="padding: 0; margin: 0; list-style: none; display: flex; flex-direction: column; gap: 12px;">
                    <?php foreach($extra['abilities'] as $ab): ?>
                        <li style="display: flex; gap: 10px;">
                            <span style="color: #0d9488;">⚡</span>
                            <div>
                                <div style="font-weight: 600; font-size: 9.5pt; color: #111827;">• <?php echo htmlspecialchars($ab['title']); ?></div>
                                <div style="font-size: 8.5pt; color: #6b7280; margin-top: 2px;"><?php echo htmlspecialchars($ab['desc']); ?></div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                 </ul>
            </div>
            <?php endif; ?>
            
            <?php if ($is_pro && ($resume['bank_name'] ?? '')): ?>
                <div style="background: #fff1f2; padding: 20px; border-radius: 8px; border: 1px dashed #fda4af;">
                    <div style="font-weight: 700; color: #be123c; font-size: 9pt; margin-bottom: 10px;">VERIFIED BANKING</div>
                    <div style="font-size: 9pt; color: #881337;">
                        <?php echo htmlspecialchars($resume['bank_name']); ?><br>
                        <?php echo htmlspecialchars($resume['acc_no']); ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <?php if(!empty($extra['achievements'])): ?>
    <!-- Achievements Footer Section for AI Smart -->
    <div style="padding: 30px 50px; background: #fafafa; border-top: 1px solid #eee;">
        <h3 style="font-size: 12pt; font-weight: 700; color: #111827; letter-spacing: 1px; margin-bottom: 20px;">KEY ACHIEVEMENTS</h3>
        <ul style="padding-left: 20px; margin: 0; display: grid; grid-template-columns: 1fr 1fr; gap: 20px; font-size: 10pt; color: #4b5563; line-height: 1.5;">
            <?php foreach($extra['achievements'] as $a): ?>
                <li><?php echo htmlspecialchars($a); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
</div>
