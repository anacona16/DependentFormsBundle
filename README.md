DependentFormsBundle
====================

DependentFormsBundle provides dependent form type.

## Important

This bundle is based on: [ShtumiUsefulBundle](https://github.com/shtumi/ShtumiUsefulBundle) updated for use it with Symfony >= 3.4, 4.0 and 5.0 based on Flex.

Only Dependent Forms included, no Ajax Autocomplete, no DateRange form type, no DQL custom functions.

If you are looking for a previous Symfony version see the branch symfony-33.

## Installation

Open a command console, enter your project directory and execute the following command to download the latest stable version of this bundle:

```cli
$ composer require anacona16/dependent-forms-bundle
```

This command requires you to have Composer installed globally, as explained in the [Composer documentation](https://getcomposer.org/doc/00-intro.md).

### Verify DependentFormsBundle was added to you kernel
```php
// config/bundles.php
return [
    // ...
    Anacona16\Bundle\DependentFormsBundle\DependentFormsBundle::class => ['all' => true],
    // ...
];
```

### Import routes

```yml
# config/routes/dependent_forms.yaml
anacona16_dependent_forms:
    resource: '@DependentFormsBundle/Resources/config/routing.xml'
```

### Update your configuration

#### Add form theming to twig
```yml
# config/packages/twig.yaml
twig:
    ...
    form_themes:
        - '@DependentForms/Form/fields.html.twig'
```

### Prepare the Web Assets of the Bundle

```cli
php bin/console assets:install --symlink
```

### Load jQuery to your views
```html
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
```

## Documentation

[Read documentation](https://github.com/anacona16/DependentFormsBundle/blob/master/Resources/doc/dependent_forms.md)
