<?php
namespace MegaOptim\RapidCache\Traits\Plugin;

use MegaOptim\RapidCache\Classes;

trait WcpWooCommerceUtils
{
    /**
     * Automatically clears cache file for a WooCommerce Product when its stock is changed.
     *
     * @since 1.0.0
     *
     * @attaches-to `woocommerce_product_set_stock` hook.
     *
     * @param \WC_Product $product A WooCommerce WC_Product object
     *
     * @return void
     */
    public function autoClearPostCacheOnWooCommerceSetStock($product)
    {
        $counter = 0; // Initialize.

        if (!is_null($done = &$this->cacheKey('autoClearPostCacheOnWooCommerceSetStock'))) {
            return; // Already did this.
        }
        $done = true; // Flag as having been done.

        if (class_exists('\\WooCommerce')) {
            $product_id = $this->getProductId($product);
            $counter += $this->autoClearPostCache($product_id);
        }
    }

    /**
     * Automatically clears cache file for a WooCommerce Product when its stock status is changed.
     *
     * @since 1.0.0
     *
     * @attaches-to `woocommerce_product_set_stock_status` hook.
     *
     * @param string|int $product_id A WooCommerce product ID.
     *
     * @return void
     */
    public function autoClearPostCacheOnWooCommerceSetStockStatus($product_id)
    {
        $counter = 0; // Initialize.

        if (!is_null($done = &$this->cacheKey('autoClearPostCacheOnWooCommerceSetStockStatus'))) {
            return; // Already did this.
        }
        $done = true; // Flag as having been done.

        if (class_exists('\\WooCommerce')) {
            $product_id = $this->getProductId($product_id);
            $counter += $this->autoClearPostCache($product_id);
        }
    }

    /**
     * Retrieve the product ID safely.
     *
     * Compatible with older versions
     *
     * @param $product
     *
     * @since 1.0.0
     *
     * @return int|string
     */
    private function getProductId($product) {
        $id = $product;
        if(is_numeric($product)) {
            $id = $product;
        } else if(is_object($product)) {
            if(method_exists($product, 'get_id')) {
                $id = $product->get_id();
            } else {
                $id = isset($product->id) ? $product->id : $product;
            }
        }
        return $id;
    }
}
