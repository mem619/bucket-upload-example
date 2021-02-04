<?php
 
// Libreria GCS
require_once 'vendor/autoload.php'; 
use Google\Cloud\Storage\StorageClient;
$BUCKET_NAME = '';

// NOTE: to create private key JSON file: 
// https://console.cloud.google.com/iam-admin/serviceaccounts?project=  
$privateKeyFileContent = '';

function uploadFile($fileContent, $cloudPath) {
  $bucketName = $GLOBALS['BUCKET_NAME'];
  $privateKeyFileContent = $GLOBALS['privateKeyFileContent'];
  // conectarse a Google Cloud Storage utilizando una clave privada como autenticación
  try {
    $storage = new StorageClient([
      'keyFile' => json_decode($privateKeyFileContent, true)
    ]);
  } catch (Exception $e) {
    print $e;
    return false;
  }

  $bucket = $storage->bucket($bucketName);

  $storageObject = $bucket->upload(
    $fileContent,
    ['name' => $cloudPath]
  );

  return $storageObject != null;
}
 
function getFileInfo($cloudPath) {
  $bucketName = $GLOBALS['BUCKET_NAME'];
  $privateKeyFileContent = $GLOBALS['privateKeyFileContent'];
  try {
    $storage = new StorageClient([
      'keyFile' => json_decode($privateKeyFileContent, true)
    ]);
  } catch (Exception $e) {
    print $e;
    return false;
  }

  $bucket = $storage->bucket($bucketName);
  $object = $bucket->object($cloudPath);

  return $object->info();
}

function listFiles() {
  $bucketName = $GLOBALS['BUCKET_NAME'];
  $privateKeyFileContent = $GLOBALS['privateKeyFileContent'];
  try {
    $storage = new StorageClient([
      'keyFile' => json_decode($privateKeyFileContent, true)
    ]);
  } catch (Exception $e) {
    print $e;
    return false;
  }

  $result = array();
  $bucket = $storage->bucket($bucketName);

  foreach ($bucket->objects() as $object) {
    array_push(
      $result,
      array(
          'name' => $object->name(),
          'link' => 'https://storage.googleapis.com/'.$bucketName. '/' . $object->name()
        )
    );
  }

  return $result;
}