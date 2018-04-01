# Movie-Database-Create
Movie Database-Create Script for the TUC

This Readme contains a User Manualand a Dev Manual

## User Manual <a name="userManual"></a>

1. Setup
   
2. Manage Accounts
   
   2.1. Create Accounts
   
   2.2. Delete Accounts
   
   2.3. List Accounts
   
   2.4. Generate Login List
   
   2.5. Reset Password
   
2. Manage Hosts

   2.1. List Hosts
   
   2.2. Add Host
   
   2.3. Remove Host
   
3. Show Grants

4. Purge Database Server
   
### Setup 

For first time use a setup has to be performed. First go to /setup or press this link on the homepage:

![alt text](https://github.com/leifkuhl/Movie-Database-Create/blob/master/ReadmeImages/1%20Setup.PNG)

After pressing the setup button the default Account is created with the default name and password. Afterwards you will be redirected to login and to take the next and last step of the setup. Alternatively you can click the link below the previous or go to /setupHosts. Now you have to click the setup button again and everything is ready to go.

Note: performing this steps again will result in an error message but nothing will happen to the internal databases. To perform the setup again you have to drop the users or dbmanagerhosts table in used database.

### Manage Accounts

In the manage accounts section you can create and delete accounts, list all existing accounts on hosts, generate a password list for all accounts and reset the password for an account.

#### Create Accounts 

![alt text](https://github.com/leifkuhl/Movie-Database-Create/blob/master/ReadmeImages/2.1%20Create%20Accounts.PNG)

To create an account you have to select if it should be an student or tutor account (currently there is no difference between those two), select if it is summer or winter semester, select the year suffix (when you do not want to use the current year), the starting index (if you dont want to continue from the highest index), and the number of accounts to create.

Creating accounts will automatically create the personal databases and set permissions for them.

#### Delete Accounts

![alt text](https://github.com/leifkuhl/Movie-Database-Create/blob/17c3be8b0f8b3695e0c44529a1988816805e23b1/ReadmeImages/2.2%20Delete%20Accounts.PNG)

To delete an account and their personal databases you can select the accounts and press delete.

#### List Accounts 

To list accounts select the account type and press the button.

Note: In case a accounts ais not listed but still being able to delete, the account has been deleted but the personal databases not. Deleting the account or creating it again (with respective start index) will resolve the problem.

#### Generate Login List 

When pressing the button a list with all accounts and passwords will be generated.

#### Reset Password

To reset a password you have to type the full account name and press the reset button.

### Manage Hosts

The section to add, remove and list Hosts.

#### List Hosts

Press the button to list all existing Hosts

#### Add Hosts

Type in the name of the new host and permissions for all existing accounts will be created on the new host. All future accounts will also get permissions on the new host.

#### Remove Hosts 

Type in the name of the host to remove and permissions for all existing accounts will be deleted that host. All future accounts will no longer get permissions on that host.

### Show Grants 

Select account type and host name (when you do not want to see permissions for all hosts) to show all privilegues for selected type on that host.

### Purge Database Server 

Deletes all accounts and private databases. Tick the checkbox when you are sure you want to purge.

## Dev Manual 

1. Versions

2. Installation Help

3. Laravel Structure

### Versions

Laravel: 5.5

PHP: 7.1

MariaDB: 10.1

### Installation Help 

Install correct PHP MariaDB and Laravel Version.

To setup Laravel (install all required packages e.g. mbstring 7.1 and php-xml 7.1):

https://www.rosehosting.com/blog/install-laravel-on-ubuntu-16-04/

Merge the new project with this project (or do "composer install" in downloaded project folder) and reset proper ownership for the files. Make sure to change following functions in app/CustomDatabaseManager.php:
```
function getDefaultPwd($accName) {
   return 123456; // replace with password generating algorithm
}

setupUsers() {
   ...
   User::create([
      'name' => 'admin', // Choose default login name
      'password' => bcrypt("dummy"), // Choose default password
   ]); // replace with standard password for user
   ...
}
```
For the .env file set APP_NAME ="DB 1 Manager", APP_URL=preferedURL, DB_DATABASE=Databasename (dont let it start with db or a bug will appear), DB_USERNAME=UserThatHasRootPermissions and DB_PASSWORD=PasswordForUser.

It is important that the user hast root permissions (ALL ON *.* WITH GRANT OPTION) but the user itself can not be root (atleast in Ubuntu 16.04).

### Laravel Structure

The general structure  can be found on https://laravel.com/docs/5.5 folders with additional files are: /app for the CustomDatabaseManager.php, Constants.php and the statusMessage.txt; /app/Http/Controllers for the Controllers; /public/js for JavaScripts; Ressources/views for the webpages.

The files itself contain commentary.

/app/CustomDatabaseManager.php:

Contains custom database functions.

/app/StatusMessage.txt:

File to which the current status message is written to.

/app/Controllers:

Controllers for the different sites.
