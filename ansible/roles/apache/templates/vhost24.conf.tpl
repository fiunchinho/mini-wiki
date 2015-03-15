# Default Apache virtualhost template

<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot {{ doc_root }}
{% set servernames = servername.split() %}
{% for servername in servernames %}
{% if loop.first %}
    ServerName {{ servername }}
{% else %}
    ServerAlias {{ servername }}
{% endif %}
{% endfor %}

    <Directory {{ doc_root }}>
        AllowOverride All
        Require all granted
        <IfModule mod_rewrite.c>
            Options -MultiViews
            RewriteEngine On
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteRule ^ index.php [QSA,L]
        </IfModule>
        <IfModule mod_headers.c>
            RequestHeader  edit "If-None-Match" "^\"(.*)-gzip\"$" "\"$1\""
            Header  edit "ETag" "^\"(.*[^g][^z][^i][^p])\"$" "\"$1-gzip\""
        </IfModule>
    </Directory>
</VirtualHost>
