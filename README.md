SortableBehaviorBundle
=========================

Offers a sortable feature for your Symfony admin listing

### Configuration

By default, this extension works with [Gedmo DoctrineExtensions](https://github.com/Atlantic18/DoctrineExtensions), but you can change the position handler or implement your own (There are three implementations: Gedmo, ORM, and ODM): 

``` yaml
# app/config/config.yml
sortable_behavior:
    position_handler: sortable_behavior.position.orm
    position_field:
        default: sort # default value: position
        entities:
            AppBundle\Entity\Foobar: order
            AppBundle\Entity\Baz: rang
    sortable_groups:
        entities:
            AppBundle\Entity\Baz: [ group ]
            
```

### Normal use
In order to use this bundle on sonata, you need to configure an action
with a custom template.

```php
<?php

    // ClientAdmin.php
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('enabled')
            ->add('_action', null, array(
                'actions' => array(
                    'move' => array(
                        'template' => 'SortableBehaviorBundle:sort.html.twig',
                        'enable_top_bottom_buttons' => true, //optional
                    ),
                ),
            ))
        ;
    }
```  

#### Use a draggable list instead of up/down buttons
In order to use a draggable list instead of up/down buttons, change the template in the ```move``` action to ```SortableBehaviorBundle:sort_drag_drop.html.twig```.

```php
<?php

    // ClientAdmin.php
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('enabled')
            ->add('_action', null, array(
                'actions' => array(
                    'move' => array(
                        'template' => 'SortableBehaviorBundle:sort_drag_drop.html.twig',
                        'enable_top_bottom_buttons' => true, // optional
                    ),
                ),
            ))
        ;
    }
```    
Also include the JavaScript needed for this to work, in your ```theme.yml``` file, add these two lines:
```yml
    //...
    javascripts:
        - bundles/sortablebehavior/js/jquery-ui.min.js // if you haven't got jQuery UI yet.
        - bundles/sortablebehavior/js/init.js
```

Adding the JavaScript and the template, will give you the possibility to drag items in a tablelist.
In case you need it, this plugin fires to jQuery events when dragging is done on the ```$(document)``` element, so if you want to add custom notification, that is possible. Also, when dragging the ```<body>``` gets an ```is-dragging``` class. This class is removed when you stop dragging. This could by quite handy if you have some custom js/css.
```
SortableBehaviorBundle.success
SortableBehaviorBundle.error
```
