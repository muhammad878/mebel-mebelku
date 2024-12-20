<?php
   require 'vendor/autoload.php'; // Pastikan Anda sudah menginstal MongoDB PHP Library

   function getMongoDBConnection() {
       try {
           $client = new MongoDB\Client("mongodb://localhost:27017"); // Ganti dengan URI MongoDB Anda
           return $client->mebel_didin; // Ganti dengan nama database Anda
       } catch (Exception $e) {
           echo "Koneksi database gagal: " . $e->getMessage();
           exit;
       }
   }
   ?>