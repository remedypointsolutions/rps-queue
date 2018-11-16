# Yii2 SQS Support Component

## 1. Install

Add the following code to the required section of your composer.json file:

    "repositories": [
        {
            "type": "git",
            "url": "git@github.com:remedypointsolutions/rps-queue.git"
        }
    ],
    "require": {
        "remedypointsolutions/rps-queue": "@dev"
    },

## 2. Configure

Add following lines to your params.php file and configure the queues for your AWS account:

    'queue' => [
        'YourQueueName' => [
            'url' => 'xxxxxxx,
            'config' => [
                'key' => 'xxxxxxx',
                'secret' => 'xxxxxxx',
                'region' => 'xxxxxxx',
                'version' => 'latest'
            ]
        ]
    ],



## 3. Update database schema

The last thing you need to do is updating your database schema by applying the migrations. Make sure that you have properly configured db application component and run the following command:

    $ php yii migrate/up --migrationPath=@vendor/rps/sqs/migrations
