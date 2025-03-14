<?php
use Aws\S3\S3Client;
use Aws\Exception\AwsException;
class S3Service{
    private $s3handler;
    private $ovh;
    public function __construct()
    {
        $this->ovh    =   AdiantiApplicationConfig::get()['ovh'];
        
        $this->s3handler = new S3Client([
            'version' => 'latest',
            'region' => $this->ovh['region'],
            'endpoint' => $this->ovh['endpoint'],
            'credentials' => [
                'key' => $this->ovh['accessKey'],
                'secret' => $this->ovh['secretKey'],
            ],
            'use_path_style_endpoint' => true,
        ]);
    }
    public function fileUploadSse($source, $key)
    {
        try{
            return $this->s3handler->putObject([
                'Bucket'    =>  $this->ovh['bucket'],
                'Key'       =>  $key,
                'SourceFile'    =>  $source,
                'ServerSideEncryption' => 'AES256', // SSE-OMK na OVH é configurado usando este parâmetro
            ]);
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
    public function fileGetTempLink(string $key, string $expiration = '+2 hours'):string
    {
        try{
            $cmd = $this->s3handler->getCommand('GetObject', [
                'Bucket' => $this->ovh['bucket'],
                'Key' => $key
            ]);
            $request = $this->s3handler->createPresignedRequest($cmd, $expiration);
            return (string) $request->getUri();
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }
}