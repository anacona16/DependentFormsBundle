DependentFormsBundle
====================

DependentFormsBundle provides dependent form type.

## Important

This bundle is based on code from: [ShtumiUsefulBundle](https://github.com/shtumi/ShtumiUsefulBundle) updated for use it with Symfony >= 2.8 and 3.0

Just Dependent Forms included, no Ajax Autocomplete, no DateRange form type, no DQL custom functions.

## Installation

**Requirements**

  * Symfony 2.8+ applications (Silex not supported).

Open a command console, enter your project directory and execute the following command to download the latest stable version of this bundle:

```cli
$ composer require anacona16/dependent-forms-bundle
```

This command requires you to have Composer installed globally, as explained in the [Composer documentation](https://getcomposer.org/doc/00-intro.md).

### Add DependentFormsBundle to your application kernel
```php
// app/AppKernel.php
public function registerBundles()
{
    return array(
        // ...
        new Anacona16\Bundle\DependentFormsBundle\DependentFormsBundle(),
        // ...
    );
}
```

### Import routes

```
# app/config/routing.yml
anacona16_dependent_forms:
    resource: '@DependentFormsBundle/Resources/config/routing.xml'
```

### Update your configuration

#### Add form theming to twig
```yml
twig:
    ...
    form_themes:
        - 'DependentFormsBundle:Form:fields.html.twig'
```

### Prepare the Web Assets of the Bundle

```cli
php bin/console assets:install --symlink
```

Update your configuration in accordance with [using DependentFormsBundle](https://github.com/anacona16/DependentFormsBundle/blob/master/Resources/doc/index.md)

### Load jQuery to your views
```html
    <script src="http://code.jquery.com/jquery-1.9.1.min.js" type="text/javascript"></script>
```
