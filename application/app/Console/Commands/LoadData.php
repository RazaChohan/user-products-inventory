<?php

namespace App\Console\Commands;

use Dal\Interfaces\ProductRepository;
use Dal\Interfaces\UserRepository;
use Illuminate\Support\Facades\File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoadData extends Command
{
    /***
     * User repository
     *
     * @var UserRepository
     */
    private $userRepository;
    /***
     * Product Repository
     *
     * @var ProductRepository
     */
    private $productRepository;
    /***
     * Inserted users
     *
     * @var array
     */
    private $insertedUsers;
    /***
     * Inserted Products
     *
     * @var array
     */
    private $insertedProducts;
    /***
     * The name and signature of the console command
     * @var string
     */
    protected $signature = 'load:data {--files=users.csv,products.csv,purchased.csv} {--folder_path=./storage/data}';
    /***
     * The console command description
     *
     * @var string
     */
    protected $description = 'Load data into database from csv files options: --files=comma separated file names --folder_path = path of files';

    /***
     * LoadData constructor.
     *
     * @param UserRepository $userRepository
     * @param ProductRepository $productRepository
     *
     */
    public function __construct(UserRepository $userRepository, ProductRepository $productRepository)
    {
        $this->userRepository = $userRepository;
        $this->productRepository = $productRepository;
        parent::__construct();
    }

    /***
     * Execute the console command
     *
     */
    public function handle()
    {
        $files = explode(',', $this->option('files'));
        $folderPath = $this->option('folder_path');
        //Check each file passed in options and insert data
        foreach ($files as $file) {
            if (File::exists($folderPath . '/' . $file)) {
                $dataToInsert = $this->parseCSV($folderPath . '/' . $file);
                //Insert data in DB based on file name
                if (Str::contains(strtolower($file), 'purchased')) {
                    $this->addUserProducts($dataToInsert);
                    $this->info('Purchased products inserted successfully');
                } elseif (Str::contains(strtolower($file), 'users')) {
                    $this->insertUsers($dataToInsert);
                    $this->info('Users data inserted successfully');
                } elseif (Str::contains(strtolower($file), 'products')) {
                    $this->insertProducts($dataToInsert);
                    $this->info('Products data inserted successfully');
                }
            }
        }
        $this->info('Data loading finished successfully');
    }

    /**
     * Parse the CSV file to create an array of alerts
     *
     * @param string $csvContents
     *
     * @return array
     */
    private function parseCSV($csvContents)
    {
        $rows = array_map('str_getcsv', file($csvContents));
        $header = array_shift($rows);
        $csv = array();
        foreach ($rows as $row) {
            $csv[] = array_combine($header, $row);
        }
        return $csv;
    }

    /***
     * Insert users
     *
     * @param $data
     */
    private function insertUsers($data)
    {
        //Insert data in user table using users repository
        foreach ($data as $user) {
            if (!empty($user['name']) && !empty($user['email']) && !empty($user['password'])) {
                $this->insertedUsers[$user['id']] = $this->userRepository->insertUser($user['name'], $user['email'],
                    Hash::make($user['password']));
            }
        }
    }

    /***
     * Insert products
     *
     * @param $data
     */
    private function insertProducts($data)
    {
        //Insert data in products using products repository
        foreach ($data as $product) {
            if (!empty($product['name']) && !empty($product['sku'])) {
                $this->insertedProducts[$product['sku']] = $this->productRepository->insertProduct($product['name'],
                    $product['sku']);
            }
        }
    }

    /***
     * Add user products
     *
     * @param $data
     */
    private function addUserProducts($data)
    {
        $userProducts = [];
        foreach ($data as $userProduct) {
            if (!empty($userProduct['user_id']) && !empty($userProduct['product_sku'])) {
                //Check if user id & product id is already inserted in DB
                $userID = !empty($this->insertedUsers[$userProduct['user_id']]) ? $this->insertedUsers[$userProduct['user_id']] : 0;
                $productID = !empty($this->insertedProducts[$userProduct['product_sku']]) ? $this->insertedProducts[$userProduct['product_sku']] : 0;
                if ($userID > 0 && $productID > 0) {
                    $userProducts[] = ['user_id' => $userID, 'product_id' => $productID];
                }
            }
        }
        $this->userRepository->addUserProducts($userProducts);
    }

}
