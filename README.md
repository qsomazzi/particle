# Particle

[![License](https://img.shields.io/badge/license-MIT-blue.svg)](https://opensource.org/licenses/MIT)
[![Latest Stable Version](https://poser.pugx.org/qsomazzi/particle/v/stable)](https://packagist.org/packages/qsomazzi/particle)
[![Total Downloads](https://poser.pugx.org/qsomazzi/particle/downloads)](https://packagist.org/packages/qsomazzi/particle)

Particle is a versatile Symfony Bundle that provides a collection of Twig components based on the free templates from [Tabler](https://tabler.io). It offers a base layout and a set of components to streamline the development of Symfony applications.

## Features

- Easy integration of Tabler template components into Symfony projects.
- Simplified development with pre-designed UI components.
- Customizable and extendable components to fit various project needs.

## Installation

You can install Particle using [Composer](https://getcomposer.org/):

```bash
composer require qsomazzi/particle
```

## Usage

1. **Configuration**: Customize Particle's configuration options in your Symfony application's `config/packages/particle.yaml` file.

2. **Templates**: Use Particle's Twig components in your templates to build sleek and responsive UIs effortlessly.

```twig
{% extends '@Particle/base.html.twig' %}

{% block title %}Welcome to My Symfony App{% endblock %}

{% block content %}
    <div class="container">
        <h1>Hello, Particle!</h1>
        <!-- Your content here -->
    </div>
{% endblock %}
```

3. **Extend and Customize**: Extend Particle's components or create your own to tailor them to your project's specific requirements.

## Contributing

Contributions are welcome! Whether you want to report a bug, request a feature, or contribute code, please follow our [contribution guidelines](CONTRIBUTING.md).

## License

Particle is open-source software licensed under the [MIT license](LICENSE).

## Support

For questions or support, please [open an issue](https://github.com/qsomazzi/particle/issues) on GitHub.
