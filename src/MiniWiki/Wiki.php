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
        return $this->file_get_contents_lock($this->db);
    }

    public function writeArticle($article, $text)
    {
        return file_put_contents($this->db, $text, LOCK_EX);
    }

    private function fibonacci($n)
    {
        if ($n == 1 || $n == 2){
            return 1;
        }else{
            return $this->fibonacci($n - 1) + $this->fibonacci($n - 2);
        }
    }

    private function file_get_contents_lock($filename){
        $handle = fopen($filename, 'r');
        if (flock($handle, LOCK_EX)){
            $content = file_get_contents($filename);
            flock($handle, LOCK_UN);
            fclose($handle);

            return $content;
        }

        throw new \RunTimeException("Couldn't get file lock");
    }
}