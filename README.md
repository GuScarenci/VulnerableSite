## Alunos Desenvolvedores: 
- Gustavo Moura, nº USP 12547792
- Matheus Henrique Dias Cirillo,nº USP 12547750 

## Demonstração
A VM está disponível para [download](https://drive.google.com/file/d/1tKXR5LEKFbM1_dbWLw3g8Aa2eB06sNHy/view?usp=sharing) no Google Drive.

Abaixo segue um vídeo mostrando o funcionamento e vulnerabilidades do site.

[![Vídeo com demonstração do projeto](https://i9.ytimg.com/vi_webp/SEvqmmeS5eg/mqdefault.webp?v=66e8f53b&sqp=CKT4pbcG&rs=AOn4CLBqfVrcvV6Ty_MV79g2iQnsmt-H0Q)](https://youtu.be/SEvqmmeS5eg)

## Configurações
VM no Virtual Box 1.2  com o SO Ubuntu 24.04.1 LTS.  A VM foi configurada para permitir comunicação entre a máquina host e a VM.
MySQL 8.0.39
PHP 5.6.40
Apache 2.4.58

O presente projeto tem como objetivo a criação de uma aplicação web vulnerável, com foco na exploração de duas principais vulnerabilidades: SQL Injection e Cross-Site Scripting (XSS). A aplicação foi desenvolvida para rodar em uma máquina virtual configurada com Apache, PHP e MySQL, permitindo que os conhecimentos adquiridos na disciplina de Laboratório de Engenharia de Segurança sejam colocados em prática. Este relatório descreve o ambiente configurado, as vulnerabilidades exploradas, os métodos utilizados e os resultados obtidos, além de discutir possíveis formas de mitigação dessas vulnerabilidades em ambientes de produção.

Ao configurar o MYSQl com o comando `sudo mysql\_secure\_installation`, escolheram-se sempre as opções que deixassem a configuração mais insegura.

Para a execução do site como esperado, o método de autenticação padrão `caching\_sha2\_password` foi substituído pelo método `mysql\_native\_password` suportado pela versão do PHP 5.6, requerida pelo professor e, além disso,que é mais insegura.

Para que isso fosse alcançado foi alterado o arquivo:

```
/etc/mysql/mysql.conf.d/mysqld.cnf
```

Arquivo de configuração do MySQL, inserindo nele a linha: 

```
default-authentication-plugin=mysql\_native\_password   
```
