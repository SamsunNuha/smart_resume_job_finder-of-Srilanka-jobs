<?php
// user/templates/creative_designer.php
// Template 4: Creative Designer
// Stylish modern layout, Color highlights, Portfolio focus
?>
<div id="resume-a4" class="a4-page template-4" style="display: block; font-family: 'Poppins', sans-serif; background: #fff;">
    
    <?php
    $initials = '';
    if (!empty($resume['full_name'])) {
        $words = explode(' ', $resume['full_name']);
        foreach ($words as $w) { $initials .= strtoupper(substr($w, 0, 1)); }
        $initials = substr($initials, 0, 2);
    }
    ?>
    <!-- Top Creative Header -->
    <div style="background: #111; color: white; padding: 40px 50px; position: relative; clip-path: polygon(0 0, 100% 0, 100% 90%, 0 100%);">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div style="font-size: 10pt; background: #ec4899; color: white; display: inline-block; padding: 4px 12px; border-radius: 20px; font-weight: 600; margin-bottom: 15px;">
                    CREATIVE PORTFOLIO
                </div>
                <h1 style="font-size: 42pt; font-weight: 800; margin: 0; line-height: 0.9; text-transform: uppercase;">
                    <?php 
                        $names = explode(' ', $resume['full_name']);
                        echo htmlspecialchars($names[0]); 
                    ?>
                    <br>
                    <span style="color: #ec4899;"><?php echo htmlspecialchars($names[1] ?? ''); ?></span>
                </h1>
                <div style="font-size: 14pt; letter-spacing: 2px; margin-top: 15px; font-weight: 300; opacity: 0.9;">
                     <?php echo htmlspecialchars($extra['job_title'] ?? 'Digital Designer'); ?>
                </div>
                <?php if(!empty($extra['highest_degree'])): ?>
                    <div style="font-size: 11pt; color: white; opacity: 0.7; margin-top: 5px; font-weight: 300;">
                        <?php echo htmlspecialchars($extra['highest_degree']); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div style="display: flex; align-items: center; gap: 40px; min-width: 0;">
                <div style="text-align: right; font-size: 11pt; font-weight: 300; opacity: 0.9;">
                    <div style="color: #ec4899; font-weight: 600;"><?php echo strtoupper(htmlspecialchars($resume['address'])); ?></div>
                    <div style="font-size: 9pt; opacity: 0.7; margin-top: 5px;">Creative Hub</div>
                </div>

                <div style="position: relative; flex-shrink: 0; width: 140px;">
                    <?php if (!empty($resume['photo'])): ?>
                        <img src="<?php echo (strpos($resume['photo'], 'http') === 0) ? $resume['photo'] : '../uploads/resumes/' . $resume['photo']; ?>" 
                             style="width: 120px; height: 120px; border-radius: 20px; object-fit: cover; border: 4px solid #ec4899; transform: rotate(3deg); display: block;">
                        <div style="position: absolute; top: -10px; right: -10px; width: 30px; height: 30px; background: #ec4899; border-radius: 50%; border: 4px solid #111;"></div>
                    <?php else: ?>
                        <div style="width: 120px; height: 120px; background: #2563eb; color: white; display: flex; align-items: center; justify-content: center; font-size: 38pt; font-weight: 800; border-radius: 20px; border: 4px solid #ec4899; transform: rotate(3deg);">
                             <?php echo htmlspecialchars($initials); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- New White Contact Bar (Blue Clickable Links) -->
    <div style="background: white; padding: 8px 30px; border-bottom: 1px solid #f3f4f6; display: flex; flex-wrap: wrap; gap: 10px; font-size: 8pt; font-family: 'Poppins', sans-serif; align-items: center; justify-content: center;">
        <span style="color: #666; display: flex; align-items: center; gap: 4px;"><i class="fa-solid fa-phone" style="font-size: 8pt; color: #666;"></i> <?php echo htmlspecialchars($resume['phone']); ?></span>
        <span style="color: #e5e7eb;">•</span>
        <a href="mailto:<?php echo htmlspecialchars($resume['email']); ?>" style="color: #2563eb; text-decoration: none; font-weight: 500; display: flex; align-items: center; gap: 4px;"><i class="fa-solid fa-envelope" style="font-size: 8pt; color: #2563eb;"></i> <?php echo htmlspecialchars($resume['email']); ?></a>
        <?php if(!empty($extra['github'])): ?>
            <span style="color: #e5e7eb;">•</span>
            <a href="https://github.com/<?php echo htmlspecialchars($extra['github']); ?>" target="_blank" style="color: #2563eb; text-decoration: none; font-weight: 500; display: flex; align-items: center; gap: 4px;"><i class="fa-brands fa-github" style="font-size: 9pt; color: #000;"></i> github.com/<?php echo htmlspecialchars($extra['github']); ?></a>
        <?php endif; ?>
        <?php if(!empty($extra['linkedin'])): ?>
            <span style="color: #e5e7eb;">•</span>
            <a href="https://linkedin.com/in/<?php echo htmlspecialchars($extra['linkedin']); ?>" target="_blank" style="color: #2563eb; text-decoration: none; font-weight: 500; display: flex; align-items: center; gap: 4px;"><i class="fa-brands fa-linkedin" style="font-size: 9pt; color: #0077B5;"></i> linkedin.com/in/<?php echo htmlspecialchars($extra['linkedin']); ?></a>
        <?php endif; ?>
        <span style="color: #e5e7eb;">•</span>
        <span style="color: #666; display: flex; align-items: center; gap: 4px;"><i class="fa-solid fa-location-dot" style="font-size: 8pt; color: #666;"></i> <?php echo htmlspecialchars($resume['address']); ?></span>
    </div>

    <div style="display: flex; padding: 30px 50px;">
        
        <!-- Main Creative Content -->
        <div style="flex: 1; padding-right: 40px;">
            
            <div style="margin-bottom: 40px;">
                <h3 style="font-weight: 800; font-size: 16pt; margin-bottom: 20px; display: flex; align-items: center;">
                    <span style="width: 30px; height: 4px; background: #ec4899; display: block; margin-right: 15px;"></span>
                    ABOUT ME
                </h3>
                <p style="font-size: 10.5pt; color: #444; line-height: 1.8;">
                    <?php echo nl2br(htmlspecialchars($resume['summary'])); ?>
                </p>
            </div>

            <div style="margin-bottom: 40px;">
                 <h3 style="font-weight: 800; font-size: 16pt; margin-bottom: 25px; display: flex; align-items: center;">
                    <span style="width: 30px; height: 4px; background: #ec4899; display: block; margin-right: 15px;"></span>
                    EXPERIENCE
                </h3>
                
                <?php foreach ($exp as $x): ?>
                <div style="margin-bottom: 30px; border-left: 3px solid #f3f4f6; padding-left: 20px; margin-left: 10px;">
                    <div style="font-weight: 700; font-size: 12pt;"><?php echo htmlspecialchars($x['role']); ?></div>
                    <div style="color: #ec4899; font-size: 10pt; font-weight: 600; margin: 4px 0 8px 0;"><?php echo htmlspecialchars($x['company']); ?> | <?php echo htmlspecialchars($x['duration'] ?? 'Present'); ?></div>
                    <div style="font-size: 10pt; color: #555; line-height: 1.6;">
                        <?php echo nl2br(htmlspecialchars($x['desc'] ?? '')); ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

             <!-- Visual Portfolio Grid (Projects) -->
             <?php if (!empty($proj)): ?>
             <div>
                <h3 style="font-weight: 800; font-size: 16pt; margin-bottom: 25px; display: flex; align-items: center;">
                    <span style="width: 30px; height: 4px; background: #ec4899; display: block; margin-right: 15px;"></span>
                    SELECTED WORKS
                </h3>
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                    <?php foreach ($proj as $i => $p): 
                          $bg = ($i % 2 == 0) ? '#fdf2f8' : '#f0f9ff';
                          $border = ($i % 2 == 0) ? '#fbcfe8' : '#e0f2fe';
                    ?>
                    <div style="background: <?php echo $bg; ?>; padding: 20px; border-radius: 12px; border: 1px solid <?php echo $border; ?>;">
                        <div style="font-weight: 700; margin-bottom: 8px;"><?php echo htmlspecialchars($p['name']); ?></div>
                         <div style="font-size: 9pt; color: #666; line-height: 1.5;"><?php echo htmlspecialchars($p['description'] ?? ''); ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
             </div>
             <?php endif; ?>

             <!-- Academic Projects -->
             <?php if (!empty($extra['academic_projects'])): ?>
             <div style="margin-top: 40px;">
                <h3 style="font-weight: 800; font-size: 16pt; margin-bottom: 25px; display: flex; align-items: center;">
                    <span style="width: 30px; height: 4px; background: #ec4899; display: block; margin-right: 15px;"></span>
                    ACADEMIC PROJECTS
                </h3>
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <?php foreach ($extra['academic_projects'] as $ap): ?>
                    <div style="border-left: 2px dashed #ec4899; padding-left: 20px; margin-left: 10px;">
                        <div style="font-weight: 700; color: #111; margin-bottom: 5px;"><?php echo htmlspecialchars($ap['title']); ?> <?php if(!empty($ap['year'])): ?><span style="color: #ec4899; font-weight: 400; font-size: 9pt;">(<?php echo htmlspecialchars($ap['year']); ?>)</span><?php endif; ?></div>
                        <div style="font-size: 10pt; color: #555; line-height: 1.6;"><?php echo htmlspecialchars($ap['desc']); ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
             </div>
             <?php endif; ?>

             <!-- Certifications & Achievements -->
             <div style="margin-top: 40px; display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                <?php if(!empty($cert)): ?>
                <div>
                    <h3 style="font-weight: 800; font-size: 14pt; margin-bottom: 20px;">CERTIFICATIONS</h3>
                    <?php foreach($cert as $c): ?>
                    <div style="margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid #f3f4f6;">
                        <div style="font-weight: 700; font-size: 9.5pt; color: #111;"><?php echo htmlspecialchars($c['name']); ?> <?php if(!empty($c['year'])): ?><span style="color: #666; font-weight: 400; font-size: 8.5pt;">(<?php echo htmlspecialchars($c['year']); ?>)</span><?php endif; ?></div>
                        <div style="font-size: 8.5pt; color: #ec4899;"><?php echo htmlspecialchars($c['issuer']); ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <?php if(!empty($extra['achievements'])): ?>
                <div>
                    <h3 style="font-weight: 800; font-size: 14pt; margin-bottom: 20px;">ACHIEVEMENTS</h3>
                    <ul style="padding-left: 20px; margin: 0; display: flex; flex-direction: column; gap: 10px; font-size: 9.5pt; color: #444; line-height: 1.4;">
                        <?php foreach($extra['achievements'] as $a): ?>
                        <li><?php echo htmlspecialchars($a); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
             </div>

        </div>

        <!-- Sidebar Right -->
        <div style="width: 30%;">
            
            <!-- Expertise Tags -->
            <div style="margin-bottom: 40px;">
                <h3 style="font-weight: 800; font-size: 12pt; margin-bottom: 20px;">EXPERTISE</h3>
                <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                    <?php 
                    $skills = explode(',', $resume['skills']);
                    foreach($skills as $skill): if(trim($skill)):
                    ?>
                        <div style="padding: 6px 14px; background: #111; color: white; font-size: 9pt; border-radius: 4px; font-weight: 500;">
                            <?php echo htmlspecialchars(trim($skill)); ?>
                        </div>
                    <?php endif; endforeach; ?>
                </div>
            </div>

             <div style="margin-bottom: 40px;">
                 <h3 style="font-weight: 800; font-size: 12pt; margin-bottom: 20px;">EDUCATION</h3>
                 <?php foreach ($edu as $e): ?>
                 <div style="margin-bottom: 15px;">
                     <div style="font-weight: 700; font-size: 10pt; color: #111;"><?php echo htmlspecialchars($e['school']); ?> | <?php echo htmlspecialchars($e['year'] ?? ''); ?></div>
                     <div style="font-size: 9.5pt; color: #ec4899; font-weight: 600;"><?php echo htmlspecialchars($e['degree']); ?></div>
                     <?php if(!empty($e['gpa'])): ?>
                         <div style="font-size: 9pt; color: #111; margin-top: 4px;"><strong>GPA:</strong> <?php echo htmlspecialchars($e['gpa']); ?></div>
                     <?php endif; ?>
                     <?php if(!empty($e['subjects'])): ?>
                         <div style="font-size: 8.5pt; color: #555; margin-top: 4px; line-height: 1.4;"><strong>Subjects:</strong> <?php echo htmlspecialchars($e['subjects']); ?></div>
                     <?php endif; ?>
                 </div>
                 <?php endforeach; ?>
             </div>

             <!-- Languages -->
             <?php if(!empty($extra['languages'])): ?>
             <div style="margin-bottom: 40px;">
                 <h3 style="font-weight: 800; font-size: 12pt; margin-bottom: 20px;">LANGUAGES</h3>
                 <?php foreach($extra['languages'] as $l): ?>
                     <div style="display: flex; justify-content: space-between; font-size: 9.5pt; margin-bottom: 10px;">
                         <span style="font-weight: 600; text-transform: uppercase;"><?php echo htmlspecialchars($l['name']); ?></span>
                         <span style="color: #ec4899;"><?php echo htmlspecialchars($l['level']); ?></span>
                     </div>
                 <?php endforeach; ?>
             </div>
             <?php endif; ?>

             <!-- Technical Expertise -->
             <?php if(!empty($extra['tech_skills'])): ?>
             <div style="margin-bottom: 40px;">
                 <h3 style="font-weight: 800; font-size: 12pt; margin-bottom: 20px;">TECHNICAL EXPERTISE</h3>
                 <ul style="padding: 0; margin: 0; list-style: none; display: flex; flex-direction: column; gap: 15px;">
                     <?php foreach($extra['tech_skills'] as $ts): ?>
                     <li style="display: flex; gap: 10px;">
                         <span style="color: #ec4899;">•</span>
                         <div>
                             <div style="font-weight: 700; font-size: 10pt; color: #111;"><?php echo htmlspecialchars($ts['title']); ?></div>
                             <div style="font-size: 8.5pt; color: #666; margin-top: 4px;"><?php echo htmlspecialchars($ts['desc']); ?></div>
                         </div>
                     </li>
                     <?php endforeach; ?>
                 </ul>
             </div>
             <?php endif; ?>

             <!-- Abilities -->
             <?php if(!empty($extra['abilities'])): ?>
             <div style="margin-bottom: 40px;">
                 <h3 style="font-weight: 800; font-size: 12pt; margin-bottom: 20px;">ABILITIES</h3>
                 <ul style="padding: 0; margin: 0; list-style: none; display: flex; flex-direction: column; gap: 12px;">
                    <?php foreach($extra['abilities'] as $ab): ?>
                        <li style="display: flex; gap: 10px;">
                            <span style="color: #ec4899;">⚡</span>
                            <div>
                                <div style="font-weight: 600; font-size: 9.5pt; color: #111;">• <?php echo htmlspecialchars($ab['title']); ?></div>
                                <div style="font-size: 8.5pt; color: #666; margin-top: 2px; line-height: 1.4;"><?php echo htmlspecialchars($ab['desc']); ?></div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                 </ul>
             </div>
             <?php endif; ?>

             <!-- QR Code Feature (Creative) -->
             <div style="text-align: center; margin-top: 50px; background: #f9fafb; padding: 20px; border-radius: 12px;">
                 <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=<?php echo urlencode("https://lankaresumey.com/resume/" . $resume['user_id']); ?>" style="width: 80px; height: 80px; margin-bottom: 10px;">
                 <div style="font-size: 8pt; font-weight: 600; color: #555;">SCAN FOR DIGITAL PORTFOLIO</div>
             </div>

        </div>
    </div>
</div>
