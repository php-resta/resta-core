<?php

namespace Resta\Traits;

trait ConsoleColor {

    // Returns colored string
    public function getColoredString($string, $foreground_color = null, $background_color = null) {

        if(isset($this->argument['commandCall'])) return $string;

        $colored_string = "";

        // Check if given foreground color found
        if (isset($this->foreground_colors[$foreground_color])) {
            $colored_string .= "\033[" . $this->foreground_colors[$foreground_color] . "m";
        }
        // Check if given background color found
        if (isset($this->background_colors[$background_color])) {
            $colored_string .= "\033[" . $this->background_colors[$background_color] . "m";
        }

        // Add string and end coloring
        $colored_string .=  $string . "\033[0m";

        return $colored_string;
    }


    // Returns colored string information
    public function info($string, $foreground_color = 'blue', $background_color = 'white') {

        if(isset($this->argument['commandCall'])) return $string;

        $colored_string = "";

        // Check if given foreground color found
        if (isset($this->foreground_colors[$foreground_color])) {
            $colored_string .= "\033[" . $this->foreground_colors[$foreground_color] . "m";
        }
        // Check if given background color found
        if (isset($this->background_colors[$background_color])) {
            $colored_string .= "\033[" . $this->background_colors[$background_color] . "m";
        }

        // Add string and end coloring
        $colored_string .=  $string . "\033[0m";

        return ''.$colored_string.'' . PHP_EOL;
    }


    // Returns colored string information
    public function success($string, $foreground_color = 'white', $background_color = 'yellow') {

        if(isset($this->argument['commandCall'])) return $string;

        $colored_string = "";

        // Check if given foreground color found
        if (isset($this->foreground_colors[$foreground_color])) {
            $colored_string .= "\033[" . $this->foreground_colors[$foreground_color] . "m";
        }
        // Check if given background color found
        if (isset($this->background_colors[$background_color])) {
            $colored_string .= "\033[" . $this->background_colors[$background_color] . "m";
        }

        // Add string and end coloring
        $colored_string .=  $string . "\033[0m";

        return ''.$colored_string.'' . PHP_EOL;
    }


    // Returns colored string information
    public function classical($string, $foreground_color = 'red', $background_color = 'white') {

        if(isset($this->argument['commandCall'])) return $string;

        $colored_string = "";

        // Check if given foreground color found
        if (isset($this->foreground_colors[$foreground_color])) {
            $colored_string .= "\033[" . $this->foreground_colors[$foreground_color] . "m";
        }
        // Check if given background color found
        if (isset($this->background_colors[$background_color])) {
            $colored_string .= "\033[" . $this->background_colors[$background_color] . "m";
        }

        // Add string and end coloring
        $colored_string .=  $string . "\033[0m";

        return ''.$colored_string.'' . PHP_EOL;
    }

    // Returns colored string information
    public function bluePrint($string, $foreground_color = 'blue', $background_color = 'white') {

        if(isset($this->argument['commandCall'])) return $string;

        $colored_string = "";

        // Check if given foreground color found
        if (isset($this->foreground_colors[$foreground_color])) {
            $colored_string .= "\033[" . $this->foreground_colors[$foreground_color] . "m";
        }
        // Check if given background color found
        if (isset($this->background_colors[$background_color])) {
            $colored_string .= "\033[" . $this->background_colors[$background_color] . "m";
        }

        // Add string and end coloring
        $colored_string .=  $string . "\033[0m";

        return ''.$colored_string.'' . PHP_EOL;
    }


    // Returns colored string information
    public function yellowPrint($string, $foreground_color = 'black', $background_color = 'yellow') {

        if(isset($this->argument['commandCall'])) return $string;

        $colored_string = "";

        // Check if given foreground color found
        if (isset($this->foreground_colors[$foreground_color])) {
            $colored_string .= "\033[" . $this->foreground_colors[$foreground_color] . "m";
        }
        // Check if given background color found
        if (isset($this->background_colors[$background_color])) {
            $colored_string .= "\033[" . $this->background_colors[$background_color] . "m";
        }

        // Add string and end coloring
        $colored_string .=  $string . "\033[0m";

        return ''.$colored_string.'' . PHP_EOL;
    }


    // Returns colored string information
    public function blue($string, $foreground_color = 'white', $background_color = 'blue') {

        if(isset($this->argument['commandCall'])) return $string;

        $colored_string = "";

        // Check if given foreground color found
        if (isset($this->foreground_colors[$foreground_color])) {
            $colored_string .= "\033[" . $this->foreground_colors[$foreground_color] . "m";
        }
        // Check if given background color found
        if (isset($this->background_colors[$background_color])) {
            $colored_string .= "\033[" . $this->background_colors[$background_color] . "m";
        }

        // Add string and end coloring
        $colored_string .=  $string . "\033[0m";

        return ''.$colored_string.'' . PHP_EOL;
    }

    // Returns colored string information
    public function yellow($string, $foreground_color = 'blue', $background_color = 'yellow') {

        if(isset($this->argument['commandCall'])) return $string;

        $colored_string = "";

        // Check if given foreground color found
        if (isset($this->foreground_colors[$foreground_color])) {
            $colored_string .= "\033[" . $this->foreground_colors[$foreground_color] . "m";
        }
        // Check if given background color found
        if (isset($this->background_colors[$background_color])) {
            $colored_string .= "\033[" . $this->background_colors[$background_color] . "m";
        }

        // Add string and end coloring
        $colored_string .=  $string . "\033[0m";

        return ''.$colored_string.'' . PHP_EOL;
    }


    // Returns colored string information
    public function cyan($string, $foreground_color = 'magenta', $background_color = 'cyan') {

        if(isset($this->argument['commandCall'])) return $string;

        $colored_string = "";

        // Check if given foreground color found
        if (isset($this->foreground_colors[$foreground_color])) {
            $colored_string .= "\033[" . $this->foreground_colors[$foreground_color] . "m";
        }
        // Check if given background color found
        if (isset($this->background_colors[$background_color])) {
            $colored_string .= "\033[" . $this->background_colors[$background_color] . "m";
        }

        // Add string and end coloring
        $colored_string .=  $string . "\033[0m";

        return ''.$colored_string.'' . PHP_EOL;
    }


    // Returns colored string information
    public function input($string, $foreground_color = 'blue', $background_color = 'light_gray') {

        if(isset($this->argument['commandCall'])) return $string;

        $colored_string = "";

        // Check if given foreground color found
        if (isset($this->foreground_colors[$foreground_color])) {
            $colored_string .= "\033[" . $this->foreground_colors[$foreground_color] . "m";
        }
        // Check if given background color found
        if (isset($this->background_colors[$background_color])) {
            $colored_string .= "\033[" . $this->background_colors[$background_color] . "m";
        }

        // Add string and end coloring
        $colored_string .=  $string . "\033[0m";

        return ''.$colored_string.'';
    }

    // Returns colored string information
    public function error($string, $foreground_color = 'white', $background_color = 'red') {

        if(isset($this->argument['commandCall'])) return $string;

        $colored_string = "";

        // Check if given foreground color found
        if (isset($this->foreground_colors[$foreground_color])) {
            $colored_string .= "\033[" . $this->foreground_colors[$foreground_color] . "m";
        }
        // Check if given background color found
        if (isset($this->background_colors[$background_color])) {
            $colored_string .= "\033[" . $this->background_colors[$background_color] . "m";
        }

        // Add string and end coloring
        $colored_string .=  "!!!! Error : ".$string . "              \033[0m";

        return ''.$colored_string.'' . PHP_EOL;
    }
}