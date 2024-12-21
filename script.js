    document.addEventListener('DOMContentLoaded', function () {
        // انتخاب ردیف‌هایی که دارای لیبل "pa_size" هستند
        const sizeRow = document.querySelector('tr .label label[for="pa_size"]');
        if (sizeRow) {
            sizeRow.closest('tr').style.display = 'none';
        }

        // انتخاب ردیف‌هایی که دارای لیبل "pa_color" هستند
        const colorRow = document.querySelector('tr .label label[for="pa_color"]');
        if (colorRow) {
            colorRow.closest('tr').style.display = 'none';
        }
    });


    document.addEventListener('DOMContentLoaded', function () {
        const variationForm = $('.variations_form');

        // تغییر مقدار برای رنگ
        $('.color-picker-label input[type="radio"]').on('change', function () {
            const value = $(this).val();
            const attributeName = $(this).attr('name');
            const select = variationForm.find(`select[name="${attributeName}"]`);

            if (select.length) {
                select.val(value).trigger('change'); // مقداردهی و تریگر تغییر
                variationForm.trigger('check_variations'); // بررسی کامل بودن متغیرها
            }
        });

        // تغییر مقدار برای سایز
        $('.size-picker-label input[type="radio"]').on('change', function () {
            const value = $(this).val();
            const attributeName = $(this).attr('name');
            const select = variationForm.find(`select[name="${attributeName}"]`);

            if (select.length) {
                select.val(value).trigger('change'); // مقداردهی و تریگر تغییر
                variationForm.trigger('check_variations'); // بررسی کامل بودن متغیرها
            }
        });

        // فعال کردن دکمه افزودن به سبد خرید
        variationForm.on('woocommerce_variation_has_changed', function () {
            const addToCartButton = $('.single_add_to_cart_button');
            if (addToCartButton.length) {
                addToCartButton.prop('disabled', false); // فعال کردن دکمه
            }
        });
    });
