<?php

namespace Elysium001\MauticPluginCreator;

class Plugin
{
    private $bundleName;
    private $projectPath;
    private $exampleFolder;
    private $file_name_w_o_bundle;

    public function initAction()
    {
        $this->projectPath = dirname(__FILE__, 5);
        echo 'Project Path: '.$this->projectPath."\n";
        $this->exampleFolder = 'ExampleBundle';
        $bundleName          = readline('Enter Bundle Name (Give name is CamelCase do not include Bundle word. Example, ExampleCode or SampleCode etc.): ');
        if (false == strpos($bundleName, 'Bundle')) {
            $this->file_name_w_o_bundle = $bundleName;
            $bundleName                 =$bundleName.'Bundle';
        } else {
            $this->file_name_w_o_bundle = str_replace('Bundle', '', $this->bundleName);
        }
        // $bundleName = readline("Enter Bundle Name (Give name is camelcase do not include Bundle word): ");
        echo "\n".$bundleName;
        $this->bundleName = $bundleName;
        $this->authorName = readline('Enter Author Name for plugin: ');
        $configuration    = [
            'bundleName' => $bundleName,
        ];
    }

    public function initialiseBundle()
    {
        $pluginPath = $this->projectPath.'/plugins/'.$this->bundleName;
        echo "Starting copying folder to plugins ...\n";
        $copyFolderCommand = 'cp -R '.__DIR__.'/'.$this->exampleFolder.' '.$pluginPath;
        echo 'Running command : '.$copyFolderCommand."\n";
        exec($copyFolderCommand);
        echo 'Plugin folder copied successfully.'."\n";

        $this->scanDirectory($pluginPath);
    }

    private function scanDirectory($path)
    {
        // $path = scandir($path);
        // var_dump($files);
        if (file_exists($path) && is_dir($path)) {
            $files = array_diff(scandir($path), ['.', '..']);
            // var_dump($files);
            if (count($files) > 0) {
                foreach ($files as $file) {
                    echo "$path/$file";
                    echo "\n";
                    if (is_file("$path/$file")) {
                        echo $file.'<br>';
                        $this->replaceFileContent("$path/$file");
                        if ('ExampleBundle.php' == $file) {
                            rename("$path/$file", $path.'/'.$this->bundleName.'.php');
                        }
                        if ('ExampleExtension.php' == $file) {
                            rename("$path/$file", $path.'/'.$this->file_name_w_o_bundle.'Extension.php');
                        }
                    } elseif (is_dir("$path/$file")) {
                        // Recursively call the function if directories found
                        $this->scanDirectory("$path/$file");
                    }
                    // var_dump($object);
                    // if(is_dir($path."/".$object)) {
                    //   //echo "folder: ".$object."\n";
                    //   //echo "folderpath: ".$path."/".$object."\n";
                    //   //$this->scanDirectory($path."/".$object);
                    // }
                    // else{
                    //    if($object =='ExampleBundle.php'){
                    //     rename($path."/".$object, $path."/".$this->bundleName.".php");
                    //     $this->replaceFileContent($path."/".$this->bundleName.".php");
                    //    }
                    //    else{
                    //     $this->replaceFileContent($path."/".$object);
                    //    }
                    // }
                }
            }
        }
    }

    private function replaceFileContent($filePath)
    {
        $str=file_get_contents($filePath);

        // if (strpos($bundleName, 'Example') == false) {
        // replace something in the file string - this is a VERY simple example
        $str=str_replace('ExampleBundle', $this->bundleName, $str);
        $str=str_replace('ExampleExtension', $this->file_name_w_o_bundle, $str);
        $str=str_replace('Avinash Dalvi', $this->authorName, $str);
        // }
        // write the entire string
        file_put_contents($filePath, $str);
    }
}
