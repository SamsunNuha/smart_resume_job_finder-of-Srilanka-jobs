<?php
// user/templates/tech_developer.php
// Template 5: Tech Developer
// Dark accent, Github focus, Tech theme
?>
<div id="resume-a4" class="a4-page template-5" style="display: flex; font-family: 'Roboto Mono', monospace; color: #cbd5e1; background: #0f172a;">
    
    <!-- Left Sidebar (Darkest) -->
    <div style="width: 30%; background: #020617; padding: 40px 25px; border-right: 1px solid #1e293b;">
        

        <!-- Sidebar Socials -->
        <div style="margin-bottom: 40px;">
            <div style="color: #22d3ee; font-weight: 700; margin-bottom: 15px; font-size: 11pt;">> SOCIAL_LINKS</div>
            <div style="font-size: 9pt; display: flex; flex-direction: column; gap: 12px; opacity: 0.8;">
                <?php if($extra['github'] ?? ''): ?>
                    <a href="https://github.com/<?php echo htmlspecialchars($extra['github']); ?>" target="_blank" style="color: #22d3ee; text-decoration: none;"><span style="color: #64748b;">$</span> github/<?php echo htmlspecialchars($extra['github']); ?></a>
                <?php endif; ?>
                <?php if($extra['linkedin'] ?? ''): ?>
                    <a href="https://linkedin.com/in/<?php echo htmlspecialchars($extra['linkedin']); ?>" target="_blank" style="color: #22d3ee; text-decoration: none;"><span style="color: #64748b;">$</span> in/<?php echo htmlspecialchars($extra['linkedin']); ?></a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Tech Stack -->
        <div style="margin-bottom: 40px;">
             <div style="color: #22d3ee; font-weight: 700; margin-bottom: 15px; font-size: 11pt;">> TECH_STACK</div>
             <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                <?php 
                $skills = explode(',', $resume['skills']);
                foreach($skills as $skill): if(trim($skill)):
                ?>
                    <div style="background: #1e293b; color: #94a3b8; padding: 4px 10px; border-radius: 4px; font-size: 8.5pt; border: 1px solid #334155;">
                        <?php echo htmlspecialchars(trim($skill)); ?>
                    </div>
                <?php endif; endforeach; ?>
             </div>
        </div>

        <!-- Education -->
        <div>
            <div style="color: #22d3ee; font-weight: 700; margin-bottom: 15px; font-size: 11pt;">> EDUCATION</div>
            <?php foreach ($edu as $e): ?>
             <div style="margin-bottom: 20px;">
                 <div style="font-weight: 700; color: #f1f5f9; font-size: 9.5pt;"><?php echo htmlspecialchars($e['degree']); ?> (<?php echo htmlspecialchars($e['year'] ?? ''); ?>)</div>
                 <div style="font-size: 9pt; color: #64748b;"><?php echo htmlspecialchars($e['school']); ?></div>
                 <?php if(!empty($e['gpa'])): ?>
                     <div style="font-size: 8.5pt; color: #22d3ee; margin-top: 4px;"><span style="color: #64748b;">$</span> gpa --value="<?php echo htmlspecialchars($e['gpa']); ?>"</div>
                 <?php endif; ?>
                 <?php if(!empty($e['subjects'])): ?>
                     <div style="font-size: 8.5pt; color: #94a3b8; margin-top: 4px; line-height: 1.4;"><span style="color: #64748b;">$</span> list-subjects --min=4 [<?php echo htmlspecialchars($e['subjects']); ?>]</div>
                 <?php endif; ?>
             </div>
             <?php endforeach; ?>
        </div>

        <!-- Languages -->
        <?php if(!empty($extra['languages'])): ?>
        <div style="margin-top: 40px;">
             <div style="color: #22d3ee; font-weight: 700; margin-bottom: 15px; font-size: 11pt;">> LANGUAGES</div>
             <div style="font-size: 9pt; display: flex; flex-direction: column; gap: 8px; opacity: 0.8;">
                <?php foreach($extra['languages'] as $l): ?>
                    <div><span style="color: #64748b;">$</span> <?php echo htmlspecialchars($l['name']); ?> --level="<?php echo htmlspecialchars($l['level']); ?>"</div>
                <?php endforeach; ?>
             </div>
        </div>
        <?php endif; ?>

        <!-- Detailed Tech Expertise -->
        <?php if(!empty($extra['tech_skills'])): ?>
        <div style="margin-top: 40px;">
             <div style="color: #22d3ee; font-weight: 700; margin-bottom: 15px; font-size: 11pt;">> TECH_EXPERTISE</div>
             <?php foreach($extra['tech_skills'] as $ts): ?>
             <div style="margin-bottom: 15px;">
                 <div style="font-size: 9pt; color: #f1f5f9; font-weight: 700;">// <?php echo htmlspecialchars($ts['title']); ?></div>
                 <div style="font-size: 8.5pt; color: #94a3b8; margin-top: 4px; line-height: 1.4;"><?php echo htmlspecialchars($ts['desc']); ?></div>
             </div>
             <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Abilities -->
        <?php if(!empty($extra['abilities'])): ?>
        <div style="margin-top: 40px;">
             <div style="color: #22d3ee; font-weight: 700; margin-bottom: 15px; font-size: 11pt;">> ABILITIES</div>
             <div style="font-size: 9pt; display: flex; flex-direction: column; gap: 10px;">
                <?php foreach($extra['abilities'] as $ab): ?>
                    <div style="border-left: 2px solid #22d3ee; padding-left: 10px;">
                        <div style="color: #f1f5f9; font-weight: 600;"><?php echo htmlspecialchars($ab['title']); ?></div>
                        <div style="color: #64748b; font-size: 8.5pt; margin-top: 2px;"><?php echo htmlspecialchars($ab['desc']); ?></div>
                    </div>
                <?php endforeach; ?>
             </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Main Content -->
    <div style="flex: 1; padding: 50px 40px;">
        
        <?php
        $initials = '';
        if (!empty($resume['full_name'])) {
            $words = explode(' ', $resume['full_name']);
            foreach ($words as $w) { $initials .= strtoupper(substr($w, 0, 1)); }
            $initials = substr($initials, 0, 2);
        }
        ?>
        <!-- Header -->
        <div style="border-bottom: 1px solid #334155; padding-bottom: 20px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <h1 style="color: #f8fafc; font-size: 32pt; margin: 0 0 10px 0; font-family: 'Inter', sans-serif; font-weight: 800;">
                    <?php echo htmlspecialchars($resume['full_name']); ?>
                    <span style="color: #22d3ee;">.</span>
                </h1>
                 <div style="color: #94a3b8; font-size: 14pt;">
                    <?php echo htmlspecialchars($extra['job_title'] ?? 'Full Stack Developer'); ?>
                </div>
                <?php if(!empty($extra['highest_degree'])): ?>
                    <div style="color: #64748b; font-size: 10pt; margin-top: 5px; font-family: 'Roboto Mono', monospace;">
                        <span style="color: #22d3ee;">$</span> qualification --latest="<?php echo htmlspecialchars($extra['highest_degree']); ?>"
                    </div>
                <?php endif; ?>
            </div>

            <div style="flex-shrink: 0; width: 120px;">
                <?php if (!empty($resume['photo'])): ?>
                    <img src="<?php echo (strpos($resume['photo'], 'http') === 0) ? $resume['photo'] : '../uploads/resumes/' . $resume['photo']; ?>" 
                         style="width: 120px; height: 120px; border-radius: 8px; border: 3px solid #22d3ee; object-fit: cover; padding: 4px; background: #0f172a; display: block;">
                <?php else: ?>
                    <div style="width: 120px; height: 120px; background: #1e293b; color: #22d3ee; display: flex; align-items: center; justify-content: center; font-size: 36pt; font-weight: 700; border: 3px solid #22d3ee; border-radius: 8px;">
                        <?php echo htmlspecialchars($initials); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div style="background: #1e293b; padding: 6px 15px; border-radius: 8px; margin-bottom: 20px; font-size: 8pt; display: flex; flex-wrap: wrap; gap: 8px; font-family: 'Roboto Mono', monospace; border: 1px solid #334155; align-items: center;">
            <span style="color: #94a3b8; display: flex; align-items: center; gap: 4px;"><i class="fa-solid fa-phone" style="font-size: 8pt; color: #22d3ee;"></i> <?php echo htmlspecialchars($resume['phone']); ?></span>
            <span style="color: #334155;">|</span>
            <a href="mailto:<?php echo htmlspecialchars($resume['email']); ?>" style="color: #22d3ee; text-decoration: none; display: flex; align-items: center; gap: 4px;"><i class="fa-solid fa-envelope" style="font-size: 8pt; color: #22d3ee;"></i> <?php echo htmlspecialchars($resume['email']); ?></a>
            <span style="color: #334155;">|</span>
            <span style="color: #94a3b8; display: flex; align-items: center; gap: 4px;"><i class="fa-solid fa-location-dot" style="font-size: 8pt; color: #22d3ee;"></i> <?php echo htmlspecialchars($resume['address']); ?></span>
            <?php if(!empty($extra['github'])): ?>
                <span style="color: #334155;">|</span>
                <a href="https://github.com/<?php echo htmlspecialchars($extra['github']); ?>" target="_blank" style="color: #22d3ee; text-decoration: none; display: flex; align-items: center; gap: 4px;"><i class="fa-brands fa-github" style="font-size: 9pt; color: #fff;"></i> github.com/<?php echo htmlspecialchars($extra['github']); ?></a>
            <?php endif; ?>
            <?php if(!empty($extra['linkedin'])): ?>
                <span style="color: #334155;">|</span>
                <a href="https://linkedin.com/in/<?php echo htmlspecialchars($extra['linkedin']); ?>" target="_blank" style="color: #22d3ee; text-decoration: none; display: flex; align-items: center; gap: 4px;"><i class="fa-brands fa-linkedin" style="font-size: 9pt; color: #0077B5;"></i> in/<?php echo htmlspecialchars($extra['linkedin']); ?></a>
            <?php endif; ?>
        </div>

        <!-- About -->
        <div style="margin-bottom: 40px;">
             <h3 style="color: #f1f5f9; font-size: 14pt; font-weight: 700; margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                <span style="color: #22d3ee;">#</span> README.md
            </h3>
            <p style="color: #94a3b8; line-height: 1.7; font-size: 10pt;">
                <?php echo nl2br(htmlspecialchars($resume['summary'])); ?>
            </p>
        </div>

        <!-- Experience -->
        <div style="margin-bottom: 40px;">
            <h3 style="color: #f1f5f9; font-size: 14pt; font-weight: 700; margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
                <span style="color: #22d3ee;">#</span> git log --experience
            </h3>
            
            <?php foreach ($exp as $x): ?>
            <div style="margin-bottom: 35px; border-left: 2px solid #334155; padding-left: 20px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                    <div style="color: #f1f5f9; font-weight: 700; font-size: 12pt;"><?php echo htmlspecialchars($x['role']); ?></div>
                </div>
                <div style="color: #22d3ee; font-size: 10pt; margin-bottom: 10px;"><?php echo htmlspecialchars($x['company']); ?> <span style="color: #64748b;">// <?php echo htmlspecialchars($x['duration'] ?? 'Present'); ?></span></div>
                <div style="color: #94a3b8; font-size: 10pt; line-height: 1.6;">
                    <?php echo nl2br(htmlspecialchars($x['desc'] ?? '')); ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Projects -->
        <?php if (!empty($proj)): ?>
        <div style="margin-bottom: 40px;">
             <h3 style="color: #f1f5f9; font-size: 14pt; font-weight: 700; margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
                <span style="color: #22d3ee;">#</span> git submodule status
            </h3>
            <div style="display: grid; grid-template-columns: 1fr; gap: 20px;">
                <?php foreach ($proj as $p): ?>
                <div style="background: #1e293b; padding: 20px; border-radius: 8px; border: 1px solid #334155;">
                    <div style="color: #f1f5f9; font-weight: 700; margin-bottom: 6px;"><?php echo htmlspecialchars($p['name']); ?></div>
                    <div style="color: #94a3b8; font-size: 9.5pt;"><?php echo htmlspecialchars($p['description'] ?? ''); ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Academic Projects -->
        <?php if (!empty($extra['academic_projects'])): ?>
        <div style="margin-top: 40px;">
            <h3 style="color: #f1f5f9; font-size: 14pt; font-weight: 700; margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
                <span style="color: #22d3ee;">#</span> academic-projects.log
            </h3>
            <?php foreach ($extra['academic_projects'] as $ap): ?>
            <div style="margin-bottom: 25px; background: #111827; padding: 15px; border-radius: 4px; border: 1px dashed #334155;">
                <div style="color: #22d3ee; font-weight: 700; margin-bottom: 5px;">[TITLE]: <?php echo htmlspecialchars($ap['title']); ?> <?php if(!empty($ap['year'])): ?><span style="color: #64748b; font-weight: 400; font-size: 8.5pt;">(<?php echo htmlspecialchars($ap['year']); ?>)</span><?php endif; ?></div>
                <div style="color: #94a3b8; font-size: 9.5pt; line-height: 1.5;"><?php echo htmlspecialchars($ap['desc']); ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Certifications & Achievements -->
        <div style="margin-top: 40px; display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
            <?php if(!empty($cert)): ?>
            <div>
                <h3 style="color: #f1f5f9; font-size: 12pt; font-weight: 700; margin-bottom: 20px;">> cert-list --all</h3>
                <?php foreach($cert as $c): ?>
                <div style="margin-bottom: 12px; border-bottom: 1px solid #1e293b; padding-bottom: 8px;">
                    <div style="color: #f1f5f9; font-size: 9.5pt; font-weight: 700;"><?php echo htmlspecialchars($c['name']); ?> <?php if(!empty($c['year'])): ?><span style="color: #64748b; font-weight: 400; font-size: 8.5pt;">(<?php echo htmlspecialchars($c['year']); ?>)</span><?php endif; ?></div>
                    <div style="color: #22d3ee; font-size: 8.5pt;"><?php echo htmlspecialchars($c['issuer']); ?></div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <?php if(!empty($extra['achievements'])): ?>
            <div>
                <h3 style="color: #f1f5f9; font-size: 12pt; font-weight: 700; margin-bottom: 20px;">> achievements.sh</h3>
                <ul style="padding-left: 20px; margin: 0; display: flex; flex-direction: column; gap: 10px; font-size: 9.5pt; color: #94a3b8; line-height: 1.4;">
                    <?php foreach($extra['achievements'] as $a): ?>
                    <li><?php echo htmlspecialchars($a); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
        </div>

    </div>
</div>
