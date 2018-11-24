<?php

namespace Resta\Traits;

trait ConsoleColor
{

    /**
     * @param $string
     * @param null $foreground_color
     * @param null $background_color
     * @return string
     */
    public function getColoredString($string, $foreground_color = null, $background_color = null)
    {
        $this->consoleLogger($string,'info');

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


    /**
     * @param $string
     * @param string $foreground_color
     * @param string $background_color
     * @return string
     */
    public function info($string, $foreground_color = 'blue', $background_color = 'white')
    {
        $this->consoleLogger($string,'info');

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

    /**
     * @param $string
     * @param string $foreground_color
     * @return string
     */
    public function red($string, $foreground_color = 'red')
    {
        $this->consoleLogger($string,'info');

        if(isset($this->argument['commandCall'])) return $string;

        $colored_string = "";

        // Check if given foreground color found
        if (isset($this->foreground_colors[$foreground_color])) {
            $colored_string .= "\033[" . $this->foreground_colors[$foreground_color] . "m";
        }

        // Add string and end coloring
        $colored_string .=  $string . "\033[0m";

        return $colored_string;
    }


    /**
     * @param $string
     * @param string $foreground_color
     * @param string $background_color
     * @return string
     */
    public function success($string, $foreground_color = 'white', $background_color = 'yellow')
    {
        $this->consoleLogger($string,'info');

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

    /**
     * @param $string
     * @param string $foreground_color
     * @param string $background_color
     * @return string
     */
    public function classical($string, $foreground_color = 'red', $background_color = 'white')
    {
        $this->consoleLogger($string,'info');

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

    /**
     * @param $string
     * @param string $foreground_color
     * @param string $background_color
     * @return string
     */
    public function bluePrint($string, $foreground_color = 'blue', $background_color = 'white')
    {
        $this->consoleLogger($string,'info');

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

    /**
     * @param $string
     * @param string $foreground_color
     * @param string $background_color
     * @return string
     */
    public function yellowPrint($string, $foreground_color = 'black', $background_color = 'yellow')
    {
        $this->consoleLogger($string,'info');

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

    /**
     * @param $string
     * @param string $foreground_color
     * @param string $background_color
     * @return string
     */
    public function blue($string, $foreground_color = 'white', $background_color = 'blue')
    {
        $this->consoleLogger($string,'info');

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

    /**
     * @param $string
     * @param string $foreground_color
     * @param string $background_color
     * @return string
     */
    public function yellow($string, $foreground_color = 'blue', $background_color = 'yellow')
    {
        $this->consoleLogger($string,'info');

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

    /**
     * @param $string
     * @param string $foreground_color
     * @param string $background_color
     * @return string
     */
    public function cyan($string, $foreground_color = 'magenta', $background_color = 'cyan')
    {
        $this->consoleLogger($string,'info');

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

    /**
     * @param $string
     * @param string $foreground_color
     * @param string $background_color
     * @return string
     */
    public function input($string, $foreground_color = 'blue', $background_color = 'light_gray')
    {
        $this->consoleLogger($string,'info');

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

    /**
     * @param $string
     * @param string $foreground_color
     * @param string $background_color
     * @return string
     */
    public function error($string, $foreground_color = 'white', $background_color = 'red')
    {
        $this->consoleLogger($string,'error');

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