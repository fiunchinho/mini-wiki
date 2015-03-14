# Installation
Save this repo in your document root. Run the following command to install the dependencies:

    $ composer install

There are 2 available endpoints
- /article/{slug}: for reading the article
- /admin/{slug}: for editing the article

# Assumptions:
- The wiki doesn't support versions of the article. Just the last (current) version is saved into disk.
- Data massaging (fibonacci) and data reading can't be done separately. So there is no point in caching the data massaging process in a memory service like Redis or Memcached.
- Editors will not try to submit unsecure input to perform attacks, like XSS or CSRF. File content is presented without being escaped.
- We have to save the content into a file. We can't use a real database.
- Only one server. But is prepared to be deployed behind a reverse proxy cache like Varnish to (dramatically) improve the performance.