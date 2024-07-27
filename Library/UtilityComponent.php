<?php

namespace Library;

use Yii;
use yii\base\Component;
use yii\helpers\FileHelper;

class UtilityComponent extends Component
{
    public function processPath($path)
    {
        $trim = trim($path);
        $dashed = str_replace(' ', '_', $trim);
        $sanitized = str_replace('.', '', $dashed);
        $comma = str_replace(',', '', $sanitized);
        $sanitized_app = str_replace("'", '', $comma);
        return $sanitized_app . \Yii::$app->security->generateRandomString(5);
    }


    public function absoluteUrl()
    {
        return \yii\helpers\Url::home(true);
    }

    public function webroot()
    {
        return \Yii::getAlias('@webroot');
    }


    public function printrr($var)
    {
        print '<pre>';
        print_r($var);
        print '<br>';
        exit('turus!!!');
    }

    function currentCtrl($ctrl)
    {
        $controller = Yii::$app->controller->id;

        if (is_array($ctrl) && in_array($controller, $ctrl)) {
            return true;
        } else if ($controller == $ctrl) {
            return true;
        } else {
            return false;
        }
    }

    public function currentaction($ctrl, $actn)
    { //modify it to accept an array of controllers as an argument--> later please
        $controller = Yii::$app->controller->id;
        $action = Yii::$app->controller->action->id;

        if ($controller == $ctrl && is_array($actn) && in_array($action, $actn)) {
            return true;
        } else if (is_array($ctrl) && in_array($controller, $ctrl)) {
            return true;
        } else if ($controller == $ctrl && $action == $actn) {
            return true;
        } else {
            return false;
        }
    }

    public function DownloadFile($base64String, $fileName)
    {
        // Decode the base64 string to binary data
        $fileData = base64_decode($base64String);

        // Generate a unique temporary file path
        $tempFilePath = Yii::$app->runtimePath . '/' . uniqid() . '_' . $fileName;

        // Save the binary data to the temporary file
        file_put_contents($tempFilePath, $fileData);

        // Set the response headers for file download
        Yii::$app->response->sendFile($tempFilePath, $fileName, [
            'mimeType' => 'application/octet-stream',
            'inline' => false,
        ]);

        // Delete the temporary file
        unlink($tempFilePath);
    }

    //Log function

    public function log($message, $name = null)
    {
        $message = print_r($message, true);
        if ($name) {
            $filename = 'log/' . $name . '.log';
        } else {
            $filename = 'log/signature.log';
        }
        if (!is_dir(dirname($filename))) {
            FileHelper::createDirectory(dirname($filename));
            chmod(dirname($filename), 0755);
        }
        $req_dump = print_r($message, TRUE);
        $fp = fopen($filename, 'a');
        fwrite($fp, $req_dump);
        fclose($fp);
    }

    public function logResult($message, $name = null)
    {
        $message = print_r($message, true);

        if ($name) {
            $filename = 'log/' . $name . '.log';
        } else {
            $filename = 'log/result.log';
        }
        $req_dump = print_r($message, TRUE);
        $fp = fopen($filename, 'a');
        fwrite($fp, $req_dump);
        fclose($fp);
    }
}
