<?php

use PHPUnit\Framework\TestCase;

class TweetTest extends TestCase
{
    private $baseUrl = 'http://localhost:8080';

    public function testCreateAndGetTweet()
    {
        // Create a new tweet
        $tweetData = [
            'author' => 'TestUser',
            'content' => 'Hello, World!'
        ];
        $options = [
            'http' => [
                'method' => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query($tweetData)
            ]
        ];
        $context = stream_context_create($options);
        $result = file_get_contents($this->baseUrl . '/tweets', false, $context);
        $createdTweet = json_decode($result, true);

        // Check that the tweet was created correctly
        $this->assertEquals($tweetData['author'], $createdTweet['author']);
        $this->assertEquals($tweetData['content'], $createdTweet['content']);

        // Get the tweet
        $result = file_get_contents($this->baseUrl . '/tweets/' . $createdTweet['id']);
        $fetchedTweet = json_decode($result, true);

        // Check that the fetched tweet matches the created tweet
        $this->assertEquals($createdTweet, $fetchedTweet);
    }
}
