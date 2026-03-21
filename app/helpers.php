<?php

if (!function_exists('shop_name')) {
    /**
     * Returns the display name for the current shop.
     * On the demo tenant, a visitor's entered name takes precedence
     * over the real tenant name stored in the database.
     */
    function shop_name(): string
    {
        return session('demo_shop_name') ?: (tenant('name') ?? 'Stoka');
    }
}
