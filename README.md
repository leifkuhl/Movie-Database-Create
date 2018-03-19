# Movie-Database-Create
Movie Database-Create Script for the TUC

This Readme contains a [User Manual](#userManual) and a [Dev Manual](#devManual)

## User Manual <a name="userManual"></a>

1. [Setup](#setup)
   
2. [Manage Accounts](#manageAccounts)
   
   2.1. [Create Accounts](#create)
   
   2.2. [Delete Accounts](#delete)
   
   2.3. [List Accounts](#listAccounts)
   
   2.4. [Generate Login List](#generate)
   
   2.5. [Reset Password](#reset)
   
2. [Manage Hosts](#manageHosts)

   2.1. [List Hosts](#listHosts)
   
   2.2. [Add Host](#add)
   
   2.3. [Remove Host](#remove)
   
3. [Show Grants](#show)

4. [Purge Database Server](#purge)
   
### Setup <a name="setup"></a>

For first time use a setup has to be performed. First go to /setup or press this link on the homepage:

![alt text](https://github.com/leifkuhl/Movie-Database-Create/blob/master/ReadmeImages/1%20Setup.PNG)

After pressing the setup button the default Account is created with the default name and password. Afterwards you will be redirected to login and to take the next and last step of the setup. Alternatively you can click the link below the previous or go to /setupHosts. Now you have to click the setup button again and everything is ready to go.

Note: performing this steps again will result in an error message but nothing will happen to the internal databases. To perform the setup again you have to drop the users or dbmanagerhosts table in used database.

### Manage Accounts <a name="manageHosts"></a>

In the manage accounts section you can create and delete accounts, list all existing accounts on hosts, generate a password list for all accounts and reset the password for an account.

#### Create Accounts <a name="create"></a>

![alt text](https://github.com/leifkuhl/Movie-Database-Create/blob/master/ReadmeImages/2.1%20Create%20Accounts.PNG)

To create an account you have to select if it should be an student or tutor account (currently there is no difference between those two), select if it is summer or winter semester, select the year suffix (when you do not want to use the current year), the starting index (if you dont want to continue from the highest index), and the number of accounts to create.

Creating accounts will automatically create the personal databases and set permissions for them.

#### Delete Accounts <a name="delete"></a>

![alt text](https://github.com/leifkuhl/Movie-Database-Create/blob/master/ReadmeImages/2.2%20Delete%20Accounts.PNG)

To delete an account and their personal databases you can select the accounts and press delete.

#### List Accounts <a name="listAccounts"></a>

To list accounts select the account type and press the button.

Note: In case a accounts ais not listed but still being able to delete, the account has been deleted but the personal databases not. Deleting the account or creating it again (with respective start index) will resolve the problem.

#### Generate Login List <a name="generate"></a>

When pressing the button a list with all accounts and passwords will be generated.

#### Reset Password <a name="reset"></a>

To reset a password you have to type the full account name and press the reset button.

### Manage Hosts <a name="manageHosts"></a>

The section to add, remove and list Hosts.

#### List Hosts <a name="add"></a>

Press the button to list all existing Hosts

#### Add Hosts <a name="add"></a>

Type in the name of the new host and permissions for all existing accounts will be created on the new host. All future accounts will also get permissions on the new host.

#### Remove Hosts <a name="remove"></a>

Type in the name of the host to remove and permissions for all existing accounts will be deleted that host. All future accounts will no longer get permissions on that host.

### Show Grants <a name="show"></a>

Select account type and host name (when you do not want to see permissions for all hosts) to show all privilegues for selected type on that host.

### Purge Database Server <a name="purge"></a>

Deletes all accounts and private databases. Tick the checkbox when you are sure you want to purge.

## Dev Manual <a name="devManual"></a>
