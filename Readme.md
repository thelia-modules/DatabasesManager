# Thelia 2 Databases Manager module

Allows managing multiple connections to databases for other modules and provides commands to parse `schema.xml` files with multiple definitions.

## Installation

### Manually…

Copy the module into `<thelia_root>/local/modules/` directory and be sure that the name of the module is `DatabasesManager`.

### … or with Composer

Add it in your main thelia `composer.json` file.

```
composer require bilhackmac/databases-manager-module:~1.0
```

### Then

Activate it in your thelia administration panel.

## Usage

### 1. schema.xml

Unmodified `schema.xml` files continue to work as expected but you can now add a new root `databases` node instead of `database`.

```xml
<databases>
    <database defaultIdMethod="native" name="thelia" namespace="Module\Namespace\Model">
        <table name="theliaTable">
            <column type="integer" name="id" required="true" primaryKey="true" autoIncrement="true"/>
            <column type="integer" name="field" required="true"/>
        </table>

        <external-schema filename="local/config/schema.xml" referenceOnly="true" />
    </database>

    <database defaultIdMethod="native" name="anotherDB" namespace="Module\Namespace\Model">
        <!-- Note database name for later -->
        <table name="externalTable">
            <column type="integer" name="id" required="true" primaryKey="true" autoIncrement="true"/>
            <column type="integer" name="field" required="true"/>
        </table>
    </database>

    <!-- Add any database node you need --->
</databases>
```

### 2. Commands

Databases manager override two Thelia 2 commands and adds one.

```
php Thelia module:generate:model <moduleName>
```

and

```
php Thelia module:generate:sql <moduleName>
```

always work as expected but now handles `databases` and multiples `database` nodes in `schema.xml` files. These commands also add an empty database configuration for every unkown database name in configuration file (e.g. : `anotherDB` in previous `schema.xml`).

```
php Thelia module:schema:recovery <moduleName>
```

can recover `schema.xml` if unexpected exit happens during any of two previous command (e.g. : `Ctrl + C`).

### 3. Configuration

Access to databases configuration by clicking on `Configure` from backoffice module manager. You can now add/edit/delete databases configurations.  
**But be careful**, configuration labels have to be the same as `schema.xml` `database` nodes name, otherwise, models will not be able to find connection to their databases.

You can define database configuration for your current environment (`prod` by default and `dev` when accessing with `index_dev.php`). Environment configuration with the same label as shared configuration overwrite it.  
It's can be useful if you don't want that `dev` environment access same external database as `prod` environment.

### 4. Overwite Thelia default configuration

**This feature seems to work, but it's not tested.**  
If you want connect to another Thelia database than the default one, labeled it `thelia`. By this way, you can have a production database and a development database.
