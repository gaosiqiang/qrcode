[TOC]
# 二维码服务

## 先决环境

- 服务器：nginx or apache
- 语言环境：>=php7

## 安装

	cd /data/wwwroot
	git clone git@github.com:gaosiqiang/qrcode.git

## nginx配置

	server {
    		charset utf-8;
	    	client_max_body_size 128M;

    		listen 80; ## listen for ipv4
    		
    		server_name my.qrcode.com;
    		root         /usr/local/nginx/html/qrcode/web;
    		index       index.php;

    		access_log  /data/logs/qrcode/access.log;
    		error_log   /data/logs/qrcode/error.log;

    		location / {
        		# Redirect everything that isn't a real file to index.php
	        	try_files $uri $uri/ /index.php$is_args$args;
    		}

	    	
    		#location ~ \.(js|css|png|jpg|gif|swf|ico|pdf|mov|fla|zip|rar)$ {
	    	#    try_files $uri =404;
	    	#}
    		#error_page 404 /404.html;

	    	# deny accessing php files for the /assets directory
    		location ~ ^/assets/.*\.php$ {
        		deny all;
	    	}
    
    		location ~ \.php$ {
        		include fastcgi_params;
	        	fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
      		  	fastcgi_pass 127.0.0.1:9000;
        		#fastcgi_pass unix:/var/run/php5-fpm.sock;
        		try_files $uri =404;
        
        		fastcgi_split_path_info ^(.+.php)(/.+)$;
        		fastcgi_index  index.php;
    		}

    		location ~* /\. {
        		deny all;
    		}
	}

> 配置中涉及的**目录**&**域名**&**log**等，自定义配置


## 使用

- 请求方式：GET
- 请求路由：img/qrcode
- 请求参数：code=二维码内容，如使用url请先将url做url_encode处理
- demo：http://qrcode.matrixfeature.com/img/qrcode?code=http%3A%2F%2Fmy.domain.com