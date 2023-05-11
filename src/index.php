<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Utopia\App;
use Utopia\Request;
use Utopia\Response;
use Utopia\Validator\Text;
use Utopia\Validator\Numeric;
use Utopia\Validator\Boolean;
use App\Repos\TweetRepository;
use App\Services\TweetService;


$tweetRepository = new TweetRepository(__DIR__ . '/../data/tweets.json');
$tweetService = new TweetService($tweetRepository);

App::error()
    ->inject('error')
    ->inject('response')
    ->action(function ($error, $response) {
        error_log($error);
        $response
            ->setStatusCode(500)
            ->send('An unexpected error occurred: ' . $error);
    });

App::get('/tweets')
    ->param('includeReplies', false, new Boolean(), 'Include replies in the results', true)
    ->inject('response')
    ->action(
        function ($includeReplies, $response) {
            global $tweetService;
            $tweets = $tweetService->getTweets($includeReplies);
            $response->json($tweets);
        }
    );

App::post('/tweets')
    ->param('author', '', new Text(256), 'Author of the tweet')
    ->param('content', '', new Text(256), 'Content of the tweet')
    ->param('replyTo', '', new Numeric(), 'The tweet ID this is a reply to', true)
    ->inject('response')
    ->action(
        function ($author, $content, $replyTo, $response) {
            global $tweetService;
            try {
                error_log('author: ' . $author);
                error_log('content: ' . $content);
                error_log('replyTo: ' . $replyTo);
                $tweet = $tweetService->createTweet($author, $content, $replyTo);
                $response->json($tweet->toArray());
            } catch (Exception $e) {
                error_log($e->getMessage());
                $response->setStatusCode(400)->send($e->getMessage());
            }
        }
    );

App::get('/tweets/:id')
    ->param('id', '', new Numeric(), 'ID of the tweet')
    ->inject('response')
    ->action(
        function ($id, $response) {
            global $tweetService;
            try {
                $tweet = $tweetService->getTweetById($id);
                $response->json($tweet->toArray());
            } catch (Exception $e) {
                $response->setStatusCode(404)->send($e->getMessage());
            }
        }
    );

App::put('/tweets/:id')
    ->param('id', '', new Numeric(), 'ID of the tweet')
    ->param('author', '', new Text(256), 'Author of the tweet')
    ->param('content', '', new Text(256), 'Content of the tweet')
    ->inject('response')
    ->action(
        function ($id, $author, $content, $response) {
            global $tweetService;
            try {
                $tweet = $tweetService->updateTweet($id, $author, $content);
                $response->json($tweet->toArray());
            } catch (Exception $e) {
                $response->setStatusCode(404)->send($e->getMessage());
            }
        }
    );

App::delete('/tweets/:id')
    ->param('id', '', new Numeric(), 'ID of the tweet')
    ->inject('response')
    ->action(
        function ($id, $response) {
            global $tweetService;
            try {
                $tweetService->deleteTweet($id);
                $response->json(['status' => 'success']);
            } catch (Exception $e) {
                $response->setStatusCode(404)->send($e->getMessage());
            }
        }
    );

$app = new App('Europe/London');
$request = new Request();
$response = new Response();

App::setMode(App::MODE_TYPE_PRODUCTION);

$app->run($request, $response);
