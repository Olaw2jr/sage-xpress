<?php

use SageDevTools\Blade\DirectivesRepository;

return [

    /*
    |---------------------------------------------------------------------
    | @istrue / @isfalse
    |---------------------------------------------------------------------
    */

    'istrue' => function ($expression) {
        if (str_contains($expression, ',')) {
            $expression = DirectivesRepository::parseMultipleArgs($expression);

            return  "<?php if (isset({$expression->get(0)}) && (bool) {$expression->get(0)} === true) : ?>".
                    "<?php echo {$expression->get(1)}; ?>".
                    '<?php endif; ?>';
        }

        return "<?php if (isset({$expression}) && (bool) {$expression} === true) : ?>";
    },

    'endistrue' => function ($expression) {
        return '<?php endif; ?>';
    },

    'isfalse' => function ($expression) {
        if (str_contains($expression, ',')) {
            $expression = DirectivesRepository::parseMultipleArgs($expression);

            return  "<?php if (isset({$expression->get(0)}) && (bool) {$expression->get(0)} === false) : ?>".
                "<?php echo {$expression->get(1)}; ?>".
                '<?php endif; ?>';
        }

        return "<?php if (isset({$expression}) && (bool) {$expression} === false) : ?>";
    },

    'endisfalse' => function ($expression) {
        return '<?php endif; ?>';
    },

    /*
    |---------------------------------------------------------------------
    | @mix
    |---------------------------------------------------------------------
    */

    'mix' => function ($expression) {
        if (ends_with($expression, ".css'")) {
            return '<link rel="stylesheet" href="<?php echo mix('.$expression.') ?>">';
        } elseif (ends_with($expression, ".js'")) {
            return '<script src="<?php echo mix('.$expression.') ?>"></script>';
        }

        return "<?php echo mix({$expression}); ?>";
    },

    /*
    |---------------------------------------------------------------------
    | @style
    |---------------------------------------------------------------------
    */

    'style' => function ($expression) {
        if (! empty($expression)) {
            return '<link rel="stylesheet" href="'.DirectivesRepository::stripQuotes($expression).'">';
        }

        return '<style>';
    },

    'endstyle' => function () {
        return '</style>';
    },

    /*
    |---------------------------------------------------------------------
    | @script
    |---------------------------------------------------------------------
    */

    'script' => function ($expression) {
        if (! empty($expression)) {
            return '<script src="'.DirectivesRepository::stripQuotes($expression).'"></script>';
        }

        return '<script>';
    },

    'endscript' => function () {
        return '</script>';
    },

    /*
    |---------------------------------------------------------------------
    | @inline
    |---------------------------------------------------------------------
    */

    'inline' => function ($expression) {
        $include = "//  {$expression}\n".
            "<?php include public_path({$expression}) ?>\n";

        if (ends_with($expression, ".css'")) {
            return "<style>\n".$include.'</style>';
        } elseif (ends_with($expression, ".js'")) {
            return "<script>\n".$include.'</script>';
        }
    },

    /*
    |---------------------------------------------------------------------
    | @js
    |---------------------------------------------------------------------
    */

    'js' => function ($expression) {
        $expression = DirectivesRepository::parseMultipleArgs($expression);

        $variable = DirectivesRepository::stripQuotes($expression->get(0));

        return  "<script>\n".
                "window.{$variable} = <?php echo is_array({$expression->get(1)}) ? json_encode({$expression->get(1)}) : '\''.{$expression->get(1)}.'\''; ?>;\n".
                '</script>';
    },

    /*
    |---------------------------------------------------------------------
    | @dump, @dd
    |---------------------------------------------------------------------
    */

    'dump' => function ($expression) {
        return "<?php dump({$expression}); ?>";
    },

    'dd' => function ($expression) {
        return "<?php dd({$expression}); ?>";
    },


    /*
    |---------------------------------------------------------------------
    | @fa
    |---------------------------------------------------------------------
    */

    'fa' => function ($expression) {
        return '<i class="fa fa-'.DirectivesRepository::stripQuotes($expression).'"></i>';
    },

];
