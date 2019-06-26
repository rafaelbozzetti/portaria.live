# ClickPortaria

Projeto de software para portarias:

- Controle de Bloco, Unidades, Moradores, Visitantes e Veículos;
- Painel de Operadore com Registros de visitantes e autoriações.

### Ambiente de Produção ( PHP/NGinx/MariaDB )


Instalando repositório conforme: https://tecadmin.net/install-php-debian-9-stretch/

```sh

apt install ca-certificates apt-transport-https 
wget -q https://packages.sury.org/php/apt.gpg -O- |  apt-key add -
echo "deb https://packages.sury.org/php/ stretch main" |  tee /etc/apt/sources.list.d/php.list

apr-get update
apt-get install mysql-server nginx php7.1 php7.1-mysql php7.1-mbstring php7.1-xml php7.1-zip nginx-light unzip git
```
	
### Clone do Projeto

```sh
git clone https://github.com/rafaelbozzetti/portaria.live.git /var/www/html/portaria.live
```

### Instale o Composer

Instale o composer conforme documentado em: https://getcomposer.org/download/

```sh
mv composer.phar /usr/bin/composer
```

### Instalando dependências do projeto

```sh
cd /var/www/html/portaria.live
composer install
```

### Arquivo de configuração
Copie o arquivo ```env.example``` na raiz do projeto para ```.env```, edite e configure conforme o ambiente.

```sh
DB_DATABASE=portaria.live
DB_USERNAME=
DB_PASSWORD=

```

### Migrações do banco

Definindo senha do usuario root do banco
```sh
mysqladmin -u root password
```

Crie o database
```sh
mysql -u root -p

mysql> create database portarialive
```

Cria toda a estrutura do do banco
```sh
/var/www/html/portaria.live/vendor/bin/phinx migrate
```

Configure o nginx para apontar para a pasta ```/var/www/html/portaria.live/public```, bem como os certificados SSL conforme necessário. Um arquivo de referência encontra-se em ```/var/www/html/portaria.live/tools/portaria.live.conf```.

Habilite os serviços para ficarem ativos
```sh
systemctl enable mariadb
systemctl enable nginx
systemctl enable php-fpm
```


