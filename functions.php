<?php
// افزودن انتخاب‌گر رنگ به فرم افزودن
add_action('pa_color_add_form_fields', 'add_color_picker_field_to_taxonomy');
function add_color_picker_field_to_taxonomy() {
    ?>
    <div class="form-field">
        <label for="term-color">انتخاب رنگ</label>
        <input type="color" name="term_color" id="term-color" value="#000000">
        <p class="description">رنگ مورد نظر را انتخاب کنید.</p>
    </div>
    <?php
}

// افزودن انتخاب‌گر رنگ به فرم ویرایش
add_action('pa_color_edit_form_fields', 'edit_color_picker_field_to_taxonomy');
function edit_color_picker_field_to_taxonomy($term) {
    $color = get_term_meta($term->term_id, 'term_color', true);
    ?>
    <tr class="form-field">
        <th scope="row" valign="top">
            <label for="term-color">انتخاب رنگ</label>
        </th>
        <td>
            <input type="color" name="term_color" id="term-color" value="<?php echo esc_attr($color ?: '#000000'); ?>">
            <p class="description">رنگ مورد نظر را انتخاب کنید.</p>
        </td>
    </tr>
    <?php
}


// ذخیره رنگ هنگام افزودن ویژگی جدید
add_action('created_pa_color', 'save_color_picker_meta');
add_action('edited_pa_color', 'save_color_picker_meta');
function save_color_picker_meta($term_id) {
    if (isset($_POST['term_color']) && !empty($_POST['term_color'])) {
        update_term_meta($term_id, 'term_color', sanitize_hex_color($_POST['term_color']));
    }
}

// افزودن ستون رنگ به لیست ویژگی‌ها
add_filter('manage_edit-pa_color_columns', 'add_color_column_to_taxonomy');
function add_color_column_to_taxonomy($columns) {
    $columns['term_color'] = 'رنگ';
    return $columns;
}

// نمایش رنگ در ستون رنگ
add_action('manage_pa_color_custom_column', 'show_color_in_taxonomy_column', 10, 3);
function show_color_in_taxonomy_column($out, $column, $term_id) {
    if ($column === 'term_color') {
        $color = get_term_meta($term_id, 'term_color', true);
        if ($color) {
            $out = '<span style="display: inline-block; width: 15px; height: 15px; background-color: ' . esc_attr($color) . '; border: 1px solid #ddd; border-radius: 50%;"></span>';
        }
    }
    return $out;
}






add_action('woocommerce_before_variations_form', 'add_color_and_size_picker_to_product_page');

function add_color_and_size_picker_to_product_page() {
    global $product;

    if (!$product->is_type('variable')) {
        return; // فقط برای محصولات متغیر اعمال شود
    }

    $attributes = $product->get_variation_attributes();

    // رنگ‌ها
    if (isset($attributes['pa_color'])) {
        $product_colors = $attributes['pa_color']; // مقادیر رنگ‌های مرتبط با محصول
        if (!empty($product_colors)) {
            echo '<div class="color-picker-container"><h4>انتخاب رنگ:</h4>';
            foreach ($product_colors as $color_slug) {
                $term = get_term_by('slug', $color_slug, 'pa_color'); // دریافت اطلاعات رنگ
                if ($term) {
                    $color_code = get_term_meta($term->term_id, 'term_color', true); // دریافت کد رنگ
                    if ($color_code) {
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
                }
            }
            echo '</div>';
        }
    }

    // سایزها
    if (isset($attributes['pa_size'])) {
        $product_sizes = $attributes['pa_size']; // مقادیر سایزهای مرتبط با محصول
        if (!empty($product_sizes)) {
            echo '<div class="size-picker-container"><h4>انتخاب سایز:</h4>';
            foreach ($product_sizes as $size_slug) {
                $term = get_term_by('slug', $size_slug, 'pa_size'); // دریافت اطلاعات سایز
                if ($term) {
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
            }
            echo '</div>';
        }
    }
}
