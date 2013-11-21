# PDO MySQL Database Module

Allows SilverStripe to use the PDO extension with MySQL databases.

## Maintainer Contact

 * Jared Kipe
   <jared (at) jaredkipe (dot) com>

## Requirements

 * SilverStripe 3.0+
 * MySQL and PHP PDO extension.



## Installation

These steps will install the latest SilverStripe stable, along with this module using [Composer](http://getcomposer.org/):

 * Install SilverStripe: `composer create-project silverstripe/installer /my/website/folder`
 * Install module: `cd /my/website/folder && composer require silverstripe/pdomysql "*"`
 * Open the SilverStripe installer by browsing to install.php, e.g. **http://localhost/silverstripe/install.php**
 * Select **PDOMySQL** in the database list and enter your SQL Server database details

## Troubleshooting


*Q: question*

A: Answer.


**Note**: Certain distributions of Linux use [SELinux](http://fedoraproject.org/wiki/SELinux) which could block access to your SQL Server database. A rule may need to be added to allow this traffic through.

