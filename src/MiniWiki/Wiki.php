<?php

namespace MiniWiki;

class Wiki
{
    public function __construct($db_path)
    {
        $this->db = $db_path;
    }

    public function getArticle($slug)
    {
        $this->fibonacci(34);
        return file_get_contents($this->db);
    }

    public function writeArticle($article, $text)
    {
        return file_put_contents($this->db, $text);
    }

    private function fibonacci($n)
    {
        if ($n == 1 || $n == 2){
            return 1;
        }else{
            return $this->fibonacci($n - 1) + $this->fibonacci($n - 2);
        }
    }
}