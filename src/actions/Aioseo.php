<?php

namespace FluentC\Actions;

use FluentC\Models\Hooks;

/*
 * AIOSEO specific modifications
 */
class Aioseo implements Hooks
{

    public function hooks()
    {

        add_filter('aioseo_canonical_url', array($this, 'aioseo_filter_canonical_url'));

    }
    //Disables the canonical_URL
    public function aioseo_filter_canonical_url()
    {
        return '';
    }
}

