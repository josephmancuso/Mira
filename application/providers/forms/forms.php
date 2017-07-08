<?php

namespace Mira;

class Form
{
    public static function start($action, $method)
    {
        echo "
        <form action='$action' method='$method'>
        ";
    }

    public static function text($name, $array = [])
    {
        if (in_array('required', $array)) {
            $required = 'required';
        }
        $classes = $array['class'];

        echo "
        <div class='form-group'>
            <label for='$name'>$name</label>
            <input id='$name' type='text' name='$name' 
            class='form-control $classes'
            $required/>
        </div>
        ";
    }

    public static function hidden($name, $array = [])
    {

        if (in_array('required', $array)) {
            $required = 'required';
        }
        $classes = $array['class'];
        $value = $array['value'];

        if (in_array('multiple', $array)) {
            foreach ($array['value'] as $value) {
                echo "
                    <input type='hidden' name='{$name}[]' value='$value'>
                ";
            }
            //return;
        } else {
            echo "
                <input type='hidden' name='$name' value='$value'>
            ";
        }
    }

    public static function select($name, $options = [])
    {
        if (in_array('required', $options)) {
            $required = 'required';
        }
        if (in_array('label', $options)) {
            $label = "<label for='$name'>$name</label>";
        }
        $classes = $options['class'];

        echo "
        <div class='form-group'>
            $label
            <select id='$name' type='text' name='$name' 
            class='form-control $classes'
            $required>";

        foreach ($options['values'] as $key => $value) {
            if (is_integer($key)) {
                $key = $value;
            }
            echo "
                <option value='$key'>$value</option>
            ";
        }

        echo"
            </select>
        </div>
        ";
    }

    public static function password($name)
    {
        if (in_array('required', $array)) {
            $required = 'required';
        }
        $classes = $array['class'];
        echo "
        <div class='form-group'>
            <label for='$name'>$name</label>
            <input id='$name' type='password' name='$name' class='form-control $classes' $required/>
        </div>
        ";
    }

    public static function email($name)
    {
        if (in_array('required', $array)) {
            $required = 'required';
        }
        $classes = $array['class'];
        echo "
        <div class='form-group'>
            <label for='$name'>$name</label>
            <input id='$name' type='email' name='$name' class='form-control $classes' $required/>
        </div>
        ";
    }

    public static function number($name)
    {
        if (in_array('required', $array)) {
            $required = 'required';
        }
        $classes = $array['class'];
        echo "
        <div class='form-group'>
            <label for='$name'>$name</label>
            <input id='$name' type='number' name='$name' class='form-control $classes' $required/>
        </div>
        ";
    }

    public static function textarea($name)
    {
        if (in_array('required', $array)) {
            $required = 'required';
        }
        $classes = $array['class'];
        echo "
        <div class='form-group'>
            <textarea class='form-control $classes' name='$name' $required></textarea>
        </div>
        ";
    }

    public static function file($name, $array = [])
    {
        if (in_array('required', $array)) {
            $required = 'required';
        }
        $classes = $array['class'];
        $max_width = $array['max_width'];
        echo "
        <div class='form-group'>
        <img id='image_$name' style='max-width: $max_width'/>
        <div class='input-group'>
            <span class='input-group-btn'>

                <span class='btn btn-default btn-file'>
                    Browse… <input type='file' id='$name' name='$name' $required>
                </span>

            </span>
            
        </div>
        </div>
        ";

        echo "<script type='text/javascript'>
            $(document).ready(function(){
                document.getElementById('$name').onchange = function () {

                var reader = new FileReader();

                reader.onload = function (e) {
                // get loaded data and render thumbnail.
                document.getElementById('image_$name').src = e.target.result;
                };

                // read the image file as a data URL.
                reader.readAsDataURL(this.files[0]);
                }
            });

        </script>";
    }

    public static function datalist($name, $options)
    {
        if (in_array('required', $array)) {
            $required = 'required';
        }
        $classes = $array['class'];
        echo "
        <div class='form-group'>
            <input list='$name' name='$name' class='form-control $classes' $required>
              <datalist id='$name'>
        ";

        foreach ($options as $option) {
            echo "<option value='$option'>";
        }

        echo"</datalist>
        </div>
        ";
    }

    public static function submit($name, $array)
    {
        $value = $array['value'];

        if (in_array('warning', $array)) {
            $btnClass = 'btn-warning';
        } elseif (in_array('danger', $array)) {
            $btnClass = 'btn-danger';
        } else {
            $btnClass = 'btn-success';
        }

        echo "
        <div class='form-group'>
            <input type='submit' class='btn $btnClass form-control'
            value='$value'
            />
        </div>
        ";
    }

    public static function button($name, $array)
    {
        $value = $array['value'];

        if (in_array('warning', $array)) {
            $btnClass = 'btn-warning';
        } elseif (in_array('danger', $array)) {
            $btnClass = 'btn-danger';
        } else {
            $btnClass = 'btn-success';
        }

        echo "
        <div class='form-group'>
            <button class='btn $btnClass form-control'>
            $value
            </button>
        </div>
        ";
    }

    public static function end()
    {
        echo "
        </form>
        ";
    }
}
