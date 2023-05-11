<?php

namespace App\Repos;

use App\Entities\TweetEntity;
use Exception;

class TweetRepository
{
    private $tweetsFilePath;

    public function __construct($tweetsFilePath)
    {
        $this->tweetsFilePath = $tweetsFilePath;
    }

    public function loadTweets()
    {
        if (!file_exists($this->tweetsFilePath)) {
            file_put_contents($this->tweetsFilePath, json_encode([]));
        }
        $content = file_get_contents($this->tweetsFilePath);
        if ($content === false) {
            throw new Exception('Failed to read tweets file');
        }
        $tweetsData = json_decode($content, true);

        $tweets = [];
        foreach ($tweetsData as $tweetData) {
            $tweets[$tweetData['id']] = new TweetEntity(
                $tweetData['id'],
                $tweetData['author'],
                $tweetData['content'],
                $tweetData['replyTo'] ?? null,
                $tweetData['created'],
                $tweetData['updated']
            );
        }
        return $tweets;
    }

    public function saveTweets($tweets)
    {
        $tweetsData = array_map(function ($tweet) {
            return $tweet->toArray();
        }, $tweets);
        $success = file_put_contents($this->tweetsFilePath, json_encode($tweetsData));
        if ($success === false) {
            throw new Exception('Failed to write tweets file');
        }
    }
}
