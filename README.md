# Installation
Clone the repo and start vagrant

    $ vagrant up

Once vagrant is up and running, log into the machine and go to the document root so you can install the dependencies:

    $ cd /var/www/miniwiki
    $ composer install

The server is listening at [http://192.168.33.99/article/Latest_plane_crash].

# Usage
- GET /article/{slug}: prints the article
- GET /admin/{slug}: prints HTML form to edit the article
- POST /admin/{slug}: endpoint to post data to edit the article


Let's see some examples. Fetching the article:

    vagrant@miniwiki:/var/www/miniwiki$ curl -i http://192.168.33.99/article/Latest_plane_crash
    
    HTTP/1.1 200 OK
    Date: Sun, 15 Mar 2015 16:57:04 GMT
    Server: Apache/2.4.12 (Ubuntu)
    Cache-Control: max-age=10, public, s-maxage=10
    x-content-digest: en0ff9d9bc5a4e7301d599533fed3fd70025e5ce35d7a8af2bcc436fb0fb5032fb
    Content-Length: 9981
    Age: 0
    X-Symfony-Cache: GET /article/Latest_plane_crash: stale, valid, store
    Vary: Accept-Encoding
    ETag: "985d29a77e3d8bbff4612b6bd4a9b7e0-gzip"
    Content-Type: text/html; charset=UTF-8

    <!DOCTYPE html>
    <html>
    <meta charset="UTF-8">
    <head><title>Latest_plane_crash</title>
    <body>
    <strong>En un lugar de la Mancha</strong> de...
    
Let's try again sending the ETag in the headers to see if we can save some bandwith:
    
    vagrant@miniwiki:/var/www/miniwiki$ curl -i -H 'If-None-Match: "985d29a77e3d8bbff4612b6bd4a9b7e0-gzip"' http://192.168.33.99/article/Latest_plane_crash
    
    HTTP/1.1 304 Not Modified
    Date: Sun, 15 Mar 2015 17:02:06 GMT
    Server: Apache/2.4.12 (Ubuntu)
    ETag: "985d29a77e3d8bbff4612b6bd4a9b7e0-gzip"
    Cache-Control: max-age=10, public, s-maxage=10
    
Now I want to edit the article. Since the ETag have not changed, I'm allowed to do this change:

    vagrant@miniwiki:/var/www/miniwiki$ curl -XPOST -d "text=New article <strong>content</strong> :)" -i -H 'If-None-Match: "985d29a77e3d8bbff4612b6bd4a9b7e0-gzip"' http://192.168.33.99/admin/Latest_plane_crash
    
    HTTP/1.1 303 See Other
    Date: Sun, 15 Mar 2015 17:06:33 GMT
    Server: Apache/2.4.12 (Ubuntu)
    Cache-Control: max-age=10, public, s-maxage=10
    Location: /article/Latest_plane_crash
    X-Symfony-Cache: POST /admin/Latest_plane_crash: pass, invalidate
    ETag: "34938c8ab13973f52ad1bcf4a66ceee3-gzip"
    Content-Length: 352
    Content-Type: text/html; charset=UTF-8

If I try to edit the article using the old ETag, the system won't allow me, and our clients (app/desktop) can handle this situation to make editors work better.

    vagrant@miniwiki:/var/www/miniwiki$ curl -XPOST -d "text=Even more <strong>content</strong> to write" -i -H 'If-Match: "985d29a77e3d8bbff4612b6bd4a9b7e0-gzip"' http://192.168.33.99/admin/Latest_plane_crash
    
    HTTP/1.1 412 Precondition Failed
    Date: Sun, 15 Mar 2015 17:13:30 GMT
    Server: Apache/2.4.12 (Ubuntu)
    Cache-Control: max-age=10, public, s-maxage=10
    X-Symfony-Cache: POST /admin/Latest_plane_crash: pass
    ETag: "62678580da5c57ceac8f0f81271442db-gzip"
    Transfer-Encoding: chunked
    Content-Type: text/html; charset=UTF-8


# Assumptions:
- The wiki doesn't support versions of the article. Just the last (current) version is saved into disk.
- Data massaging (fibonacci) and data reading can't be done separately. So there is no point in caching the data massaging process in a memory service like Redis or Memcached.
- Editors will not try to submit unsecure input to perform attacks, like XSS or CSRF. File content is presented without being escaped.
- We have to save the content into a file. We can't use a real database.
- Only one server. But is prepared to be deployed behind a reverse proxy cache like Varnish to (dramatically) improve the performance.