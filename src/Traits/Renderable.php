<?php

namespace App\Traits;

trait Renderable
{
    public function render($template, $data = [])
    {
        global $mustache;
        // session_start();

        // Ensure `user` and role information are passed
        if (isset($_SESSION['user'])) {
            $data['user'] = $_SESSION['user'];
            $data['isAdmin'] = $_SESSION['user']['role'] === 'admin';
            $data['isMember'] = $_SESSION['user']['role'] === 'member';
        }

        $tpl = $mustache->loadTemplate($template);
        echo $tpl->render($data);
    }
}
