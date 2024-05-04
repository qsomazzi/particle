# Alert

## Requirements

N\A

## Usage

```twig
    <twig:alert title="Wow! Everything worked!" />

    <twig:alert
        title="Danger now !"
        type="danger"
        icon="check"
        dismissible="{{ false }}"
    />

    <twig:alert
        title="Keep safe"
        type="warning"
        icon="check"
        dismissible="{{ false }}"
        important="{{ true }}"
    />

    <twig:alert
        title="Just to keep you in touch..."
        type="info"
        icon="info"
        description="Hello world"
    />
```
