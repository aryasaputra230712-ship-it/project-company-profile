<?php
// File untuk memicu auto-deploy cPanel
shell_exec("cd /home/vibewebs/public_html && git pull origin main");
echo "Deploy Sukses!";
