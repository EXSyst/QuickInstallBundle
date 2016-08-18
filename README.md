# EXSyst Quick Install Bundle

This symfony bundle allows to quickly register symfony bundles into your app and to configure some of them.

## How to install it?

Open a command console, enter your project directory and execute the following command:
```console
~/my-app $ composer require exsyst/quick-install-bundle 0.0.x-dev
```

Then add the bundle to your kernel:
```php
// app/AppKernel.php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new EXSyst\Bundle\QuickInstallBundle\EXSystQuickInstallBundle(),
        );

        // ...
    }
}
```

And that's all!

## How to use it?

This bundle provides a console command:
```console
~/my-app $ bin/console configure FOSRestBundle
Add the bundle "FOS\RestBundle\FOSRestBundle" to your kernel "AppKernel"? [yes]:
FOS\RestBundle\FOSRestBundle has been registered in AppKernel.
```

## What does it support?

This bundle can add any bundle to your kernel. For some it can even configure some of their features (you can try that with [`dunglas/action-bundle`](https://github.com/dunglas/DunglasActionBundle)).

## Contributing

If you want to change something or have an idea, submit an issue or open a pull request :)
