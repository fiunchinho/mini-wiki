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

    /**
     * A function like file_get_contents that makes use of locks.
     *
     * I don't want PHP to read a file while is being written by other process, so depend on
     * pessimistic locks. Instead of waiting for the lock to be released (like in writeArticle()),
     * it throws an exception if it can't get the lock. This prevents many concurrent users waiting
     * to get the lock, instead of getting cached content.
     *
     * Since we rely on HTTP Cache using a Gateway cache or Reverse Proxy cache, with stale-if-error
     * enabled, requests that can't get the file lock will see an stale cached version of the page,
     * without waiting for the lock.
     *
     * @param  string $filename
     * @return string               File content
     * @throws \RunTimeException    If can't get file lock
     */
    private function file_get_contents_lock($filename){
        $handle = fopen($filename, 'r');
        if (flock($handle, LOCK_EX | LOCK_NB)){
            $content = file_get_contents($filename);
            flock($handle, LOCK_UN);
            fclose($handle);

            return $content;
        }
        throw new \RunTimeException("Couldn't get file lock");
    }
}