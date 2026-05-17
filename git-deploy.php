<?php
// File untuk memicu auto-deploy cPanel
shell_exec("cd /home/vibewebs/vibewebs.web.id && git pull origin main");
echo "Deploy Sukses!";
