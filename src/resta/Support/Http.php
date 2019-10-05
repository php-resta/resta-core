<?php

namespace Resta\Support;

class Http
{
    /**
     * @return void|mixed
     */
    public function getInputData()
    {
        global /** @noinspection PhpUnusedLocalVariableInspection */
        $_PUT;

        /* PUT data comes in on the stdin stream */
        $putdata = fopen("php://input", "r");

        $raw_data = '';

        /* Read the data 1 KB at a time
           and write to the file */
        while ($chunk = fread($putdata, 1024))
            $raw_data .= $chunk;

        /* Close the streams */
        fclose($putdata);

        // Fetch content and determine boundary
        $boundary = substr($raw_data, 0, strpos($raw_data, "\r\n"));

        if(empty($boundary)){
            parse_str($raw_data,$data);
            $GLOBALS[ '_PUT' ] = $data;
            return;
        }

        // Fetch each part
        $parts = array_slice(explode($boundary, $raw_data), 1);
        $data = array();

        foreach ($parts as $part) {
            // If this is the last part, break
            if ($part == "--\r\n") break;

            // Separate content from headers
            $part = ltrim($part, "\r\n");
            list($raw_headers, $body) = explode("\r\n\r\n", $part, 2);

            // Parse the headers list
            $raw_headers = explode("\r\n", $raw_headers);
            $headers = array();
            foreach ($raw_headers as $header) {
                list($name, $value) = explode(':', $header);
                $headers[strtolower($name)] = ltrim($value, ' ');
            }

            // Parse the Content-Disposition to get the field name, etc.
            if (isset($headers['content-disposition'])) {
                $filename = null;
                $tmp_name = null;
                preg_match(
                    '/^(.+); *name="([^"]+)"(; *filename="([^"]+)")?/',
                    $headers['content-disposition'],
                    $matches
                );
                /** @noinspection PhpUnusedLocalVariableInspection */
                list(, $type, $name) = $matches;

                //Parse File
                if( isset($matches[4]) )
                {
                    //if labeled the same as previous, skip
                    if( isset( $_FILES[ $matches[ 2 ] ] ) )
                    {
                        continue;
                    }

                    //get filename
                    $filename = $matches[4];

                    //get tmp name
                    $filename_parts = pathinfo( $filename );
                    $tmp_name = tempnam( ini_get('upload_tmp_dir'), $filename_parts['filename']);

                    //populate $_FILES with information, size may be off in multibyte situation
                    /** @noinspection PhpUndefinedVariableInspection */
                    $_FILES[ $matches[ 2 ] ] = array(
                        'error'=>0,
                        'name'=>$filename,
                        'tmp_name'=>$tmp_name,
                        'size'=>strlen( $body ),
                        'type'=>$value
                    );

                    //place in temporary directory
                    file_put_contents($tmp_name, $body);
                }
                //Parse Field
                else
                {
                    $data[$name] = substr($body, 0, strlen($body) - 2);
                }
            }

        }
        $GLOBALS[ '_PUT' ] = $data;
        return $data;
    }

    /**
     * @param string $method
     * @return array
     */
    public function httpMethodData($method='get')
    {
        $body = [];

        if(httpMethod()==$method){
            $rawData = json_decode(request()->getContent(),1);

            $get = get();
            if(is_array($get) && count($get)){
                $body['params'] = get();
            }

            if(is_null($rawData)){

                $containerMethod = app()->get(httpMethod());

                if(is_array($containerMethod) && count($containerMethod)){
                    $inputData = $containerMethod;
                }
                else{
                    $inputData = $this->getInputData();
                }

                if(!is_null($inputData)){
                    $body['body']['form-data'] = $inputData;
                }
            }
            else{
                $body['body']['raw-data'] = $rawData;
            }
        }

        return $body;
    }
}