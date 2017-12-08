<?php

class ComposerScripts
{
    public function postInstall()
    {
        mkdir('routes', 0755);
    }

    public function postUpdate()
    {
        mkdir('routes', 0755);
    }
}
