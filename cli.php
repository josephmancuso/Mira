<?php

unset($argv[0]);

if (in_array("--new", $argv)) {
    echo "App Name: ";
    $input = str_replace(' ', '_', strtolower(trim(fgets(STDIN, 1024))));
    App::new($input);
} elseif (in_array("--model", $argv)) {
    echo "Model / Table Name: ";
    $i_model = str_replace(' ', '_', strtolower(trim(fgets(STDIN, 1024))));
    echo "Database: ";
    $i_database = str_replace(' ', '_', strtolower(trim(fgets(STDIN, 1024))));

    Model::new($i_model, $i_database);
} elseif (in_array("--install", $argv)) {
    $repo = explode("/", $argv[2]);
    $repo_user = $repo[0];
    $repo_name = $repo[1];
    echo shell_exec("cd application/app && git clone https://github.com/$repo_user/$repo_name.git" );
} else {
    echo $argv[1]." is not a command.";
}

class App
{
    public static function new($input)
    {
        $created = true;
        echo "Creating app folder ... ";
        if (mkdir("application/app/$input")) {
            echo "created successfully!\n";
        } else {
            echo "failed\n";
            $created = false;
        }

        echo "Creating app config file ... ";
        // Create config file
        if (file_put_contents("application/app/$input/config.php", "<?php

        return [
            // 'header' => '$input.base',
            // 'footer' => '$input.footer'
        ];
            ")) {
            echo "created successfully!\n";
        } else {
            echo "failed\n";
            $created = false;
        }

        // Create templates folder
        echo "Creating app template folder ... ";
        if (mkdir("application/app/$input/templates")) {
            echo "created successfully!\n";
        } else {
            echo "failed\n";
            $created = false;
        }
        
        // Create resources folder
        echo "Creating app images folder ... ";
        if (mkdir("application/app/$input/images")) {
            echo "created successfully!\n";
        } else {
            echo "failed\n";
            $created = false;
        }

        // Create resources folder
        echo "Creating app js folder ... ";
        if (mkdir("application/app/$input/js")) {
            echo "created successfully!\n";
        } else {
            echo "failed\n";
            $created = false;
        }
        
        echo "Creating app routes folder ... ";
        if (mkdir("application/app/$input/routes")) {
            echo "created successfully!\n";
        } else {
            echo "failed\n";
            $created = false;
        }

        echo "Creating app routes/routes.php file ... ";
        if (fopen("application/app/$input/routes/routes.php", "a")) {
            echo "created successfully!\n";
        } else {
            echo "failed\n";
            $created = false;
        }
        
        echo "Creating app model folder ... ";
        if (mkdir("application/app/$input/models")) {
            echo "created successfully!\n";
        } else {
            echo "failed\n";
            $created = false;
        }

        echo "Creating app models/models.php file ... ";
        if (fopen("application/app/$input/models/models.php", "a")) {
            echo "created successfully!\n";
        } else {
            echo "failed\n";
            $created = false;
        }

        echo "Creating app middleware folder ... ";
        if (mkdir("application/app/$input/middleware")) {
            echo "created successfully!\n";
        } else {
            echo "failed\n";
            $created = false;
        }

        echo "Creating app middleware/middleware.php file ... ";
        if (fopen("application/app/$input/middleware/middleware.php", "a")) {
            echo "created successfully!\n";
        } else {
            echo "failed\n";
            $created = false;
        }


        

        if ($created) {
            echo "\nApp Successfully Created!";
        } else {
            echo "\nApp could not be created ...";
        }
    }
}

class Model
{
    public static function new($model, $database)
    {
        echo "Creating model ... ";
        $file = fopen("application/models/models.php", "a");

        if ($database) {
            $database_text = "\n\tprotected ".'$db'." = '$database';\n";
        }

        fwrite($file, "\nclass $model extends Model".'{'."$database_text".'}'."\n");

        fclose($file);
        echo "created successfully\n";
    }
}
