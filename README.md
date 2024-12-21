# Custom Variation Swatches for WooCommerce

This project provides a custom solution to add, store, and display color and size attributes in WooCommerce products without relying on plugins like "Variation Swatches for WooCommerce."

## Features

- Adds custom color and size attributes to WooCommerce products.
- Stores and retrieves color codes and size values for variations.
- Displays attributes (color and size) as radio buttons or custom inputs on the product page.
- Dynamically updates variation selection and integrates with WooCommerce's default variation functionality.

## Installation

1. **Clone or Download** this repository.
2. Add the provided PHP and JavaScript code to your WordPress theme's `functions.php` file or a custom plugin.
3. Add the necessary CSS to your theme's `style.css` file.

## Implementation Steps

### 1. Adding Color Attributes to WooCommerce

Add the following PHP code to define a custom field for colors in the product attributes:

```php
add_action('product_attributes_save_attributes', 'add_color_picker_to_attributes', 10, 2);

function add_color_picker_to_attributes($product_id, $product) {
    if (isset($_POST['attribute_color_picker'])) {
        update_post_meta($product_id, '_color_picker', sanitize_text_field($_POST['attribute_color_picker']));
    }
}

add_action('woocommerce_product_after_variable_attributes', 'add_color_picker_to_variations', 10, 3);

function add_color_picker_to_variations($loop, $variation_data, $variation) {
    $color = get_post_meta($variation->ID, '_color_picker', true);
    echo '<div class="form-row form-row-full">';
    echo '<label>انتخاب رنگ</label>';
    echo '<input type="color" name="attribute_color_picker[' . $loop . ']" value="' . esc_attr($color) . '" />';
    echo '</div>';
}
```

### 2. Displaying Custom Swatches on the Product Page

Use the following PHP code to display custom swatches for colors and sizes:

```php
add_action('woocommerce_before_variations_form', 'add_color_and_size_picker_to_product_page');

function add_color_and_size_picker_to_product_page() {
    global $product;

    if (!$product->is_type('variable')) {
        return;
    }

    $attributes = $product->get_variation_attributes();

    // Display Colors
    if (isset($attributes['pa_color'])) {
        $product_colors = $attributes['pa_color'];
        if (!empty($product_colors)) {
            echo '<div class="color-picker-container"><h4>انتخاب رنگ:</h4>';
            foreach ($product_colors as $color_slug) {
                $term = get_term_by('slug', $color_slug, 'pa_color');
                $color_code = get_term_meta($term->term_id, 'term_color', true);
                echo sprintf(
                    '<label class="color-picker-label">
                        <input type="radio" name="attribute_pa_color" value="%s" style="display: none;">
                        <span class="color-circle" style="background-color: %s;" data-value="%s"></span>
                    </label>',
                    esc_attr($color_slug),
                    esc_attr($color_code),
                    esc_attr($color_slug)
                );
            }
            echo '</div>';
        }
    }

    // Display Sizes
    if (isset($attributes['pa_size'])) {
        $product_sizes = $attributes['pa_size'];
        if (!empty($product_sizes)) {
            echo '<div class="size-picker-container"><h4>انتخاب سایز:</h4>';
            foreach ($product_sizes as $size_slug) {
                $term = get_term_by('slug', $size_slug, 'pa_size');
                echo sprintf(
                    '<label class="size-picker-label">
                        <input type="radio" name="attribute_pa_size" value="%s" style="display: none;">
                        <span class="size-circle" data-value="%s">%s</span>
                    </label>',
                    esc_attr($size_slug),
                    esc_attr($size_slug),
                    esc_html($term->name)
                );
            }
            echo '</div>';
        }
    }
}
```

### 3. JavaScript for Dynamic Attribute Selection

Add this JavaScript code to handle dynamic updates when a user selects a color or size:

```javascript
document.addEventListener('DOMContentLoaded', function () {
    const variationForm = document.querySelector('.variations_form');

    // Update Color Selection
    const colorInputs = document.querySelectorAll('.color-picker-label input[type="radio"]');
    if (colorInputs) {
        colorInputs.forEach(input => {
            input.addEventListener('change', function () {
                const attributeName = input.name;
                const selectedValue = this.value;
                const select = variationForm.querySelector(`select[name="${attributeName}"]`);

                if (select) {
                    select.value = selectedValue;
                    select.dispatchEvent(new Event('change'));
                }
            });
        });
    }

    // Update Size Selection
    const sizeInputs = document.querySelectorAll('.size-picker-label input[type="radio"]');
    if (sizeInputs) {
        sizeInputs.forEach(input => {
            input.addEventListener('change', function () {
                const attributeName = input.name;
                const selectedValue = this.value;
                const select = variationForm.querySelector(`select[name="${attributeName}"]`);

                if (select) {
                    select.value = selectedValue;
                    select.dispatchEvent(new Event('change'));
                }
            });
        });
    }
});
```

### 4. CSS for Custom Swatches

Add this CSS to style the custom swatches:

```css
.color-picker-container, .size-picker-container {
    display: flex;
    gap: 15px;
    margin-top: 20px;
}

.color-circle, .size-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: inline-block;
    text-align: center;
    line-height: 40px;
    border: 2px solid #ddd;
    cursor: pointer;
    transition: all 0.3s ease;
}

.color-circle:hover, .size-circle:hover {
    border-color: #000;
}

input[type="radio"]:checked + .color-circle,
input[type="radio"]:checked + .size-circle {
    border-color: #333;
    font-weight: bold;
}
```

## Example Use Case

This solution is ideal for WooCommerce stores that want to replace default dropdowns for attributes like "Color" and "Size" with custom, visually appealing swatches.

## Contributing

Feel free to fork this repository, submit pull requests, or open issues to improve the functionality.

## License

This project is licensed under the MIT License. See the `LICENSE` file for details.
