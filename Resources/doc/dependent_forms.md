DependentFormsBundle
====================

### Dependent form type

<img src="https://github.com/anacona16/DependentFormsBundle/raw/master/Resources/doc/images/dependent_forms.png" width="50%" alt="DependentForms" title="DependentForms" align="left" />

<br><br><br><br>

Configuration
-------------

You should configure relationship between master and dependent fields for each pair:

*In this example master entity - App\Entity\Country, dependent - App\Entity\Region*

```yml
# config/packages/dependent_forms.yaml
dependent_forms:
    dependent_forms:
        region_by_country:
            class: App\Entity\Region
            parent_property: country
            property: title
            role: ROLE_USER
            no_result_msg: 'No regions found for that country'
            order_property: title
            order_direction: ASC
```

- **class** - Doctrine dependent entity.
- **role** - User role to use form type. Default: ``IS_AUTHENTICATED_ANONYMOUSLY``. It needs for security reason.
- **parent_property** - property that contains master entity with ManyToOne relationship
- **property** - Property that will used as text in select box. Default: ``title``
- **no_result_msg** - text that will be used for select box where nothing dependent entities were found for selected master entity. Default ``No results were found``. You can translate this message in ``messages.{locale}.php`` files.
- **order_property** - property that used for ordering dependent entities in selec box. Default: ``id``
- **order_direction** - You can use:
   - ``ASC`` - (**default**)
   - ``DESC`` - LIKE '%value'

Usage
=====

Simple usage
------------

Master and dependent fields should be in form together.

```php
$formBuilder
    ->add('country', EntityType::class, array(
        'class'         => 'App\Entity\Country', 
        'required'      => true,
        'mapped'        => false,
    ))
    ->add('region', DependentFormsType::class, array(
        'entity_alias'  => 'region_by_country',
        'empty_value'   => '== Choose region ==',
        'parent_field'  => 'country',
    ))
```

- **parent_field** - name of master field in your FormBuilder


Mutiple levels
--------------

You can configure multiple dependent filters:

```yml
# config/packages/dependent_forms.yaml
dependent_forms:
    dependent_forms:
        region_by_country:
            class: App\Entity\Region
            parent_property: country
            property: title
            role: ROLE_USER
            no_result_msg: 'No regions found for that country'
            order_property: title
            order_direction: ASC
        town_by_region:
            class: App\Entity\Town
            parent_property: region
            property: title
            role: ROLE_USER
            no_result_msg: 'No towns found for that region'
            order_property: title
            order_direction: ASC
```

```php
$formBuilder
    ->add('country', EntityType::class, array(
        'class'         => 'App\Entity\Country',
        'required'      => true,
    ))
    ->add('region', DependentFormsType::class, array(
        'entity_alias'  => 'region_by_country',
        'empty_value'   => '== Choose region ==', 
        'parent_field'  =>'country',
    ))
    ->add('town', DependentFormsType::class, array(
        'entity_alias'  => 'town_by_region', 
        'empty_value'   => '== Choose town ==', 
        'parent_field'  =>'region',
    ))
```

- **parent_field** - name of master field in your FormBuilder

You should load [JQuery](http://jquery.com) to your views.
